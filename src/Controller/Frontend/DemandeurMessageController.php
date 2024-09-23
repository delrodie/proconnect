<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Entity\Message;
use App\Form\MessageFormType;
use App\Security\LoginRedirectPath;
use App\Service\AllRepositories;
use App\Service\Messages;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

#[Route('/{demandeur}-conversations')]
#[isGranted('ROLE_DEMANDEUR')]
class DemandeurMessageController extends AbstractController
{
    use TargetPathTrait;
    public function __construct(
        private AllRepositories $allRepositories,
        private Utilities $utilities,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/', name: 'app_frontend_demandeur_message_list')]
    public function list($demandeur): Response
    {
        return $this->render('frontend_demandeur/message_list.html.twig',[
            'demandeur' => $this->allRepositories->getOneDemandeur($demandeur),
            'messages' => $this->allRepositories->getLastMessageByDemandeur($demandeur)
        ]);
    }

    #[Route('/{matricule}', name: 'app_frontend_demandeur_message_echange', methods: ['GET','POST'])]
    public function echange(Request $request, $matricule, $demandeur): Response
    {
        // Gestion des prestataires
        $prestataire = $this->allRepositories->getOnePrestataire($matricule);
        if (!$prestataire){
            sweetalert()->error(Messages::PRESTATAIRE_NOT_EXIST,[],"Echèc");

            return $this->redirectToRoute('app_frontend_demandeur_message_list',['demandeur' => $demandeur]);
        }

        // Gestion du demandeur
        $demandeurEntity = $this->allRepositories->getOneDemandeur(null, $this->getUser());
        if (!$demandeurEntity || $demandeurEntity->getCode() !== $demandeur){
            sweetalert()->error(Messages::DEMANDEUR_NOT_YOU,[], "Erreur d'authentification");

            return $this->redirectToRoute('app_frontend_demandeur_message_list', ['demandeur' => $demandeur]);
        }
        
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $message->setReference(time());
            $message->setEmetteur(Messages::EMETTEUR_DEMANDEUR);
            $message->setCreatedAt($this->utilities->fuseauGMT());
            $message->setDemandeur($demandeurEntity);
            $message->setPrestataire($prestataire);
            $message->setVue(false);

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_frontend_demandeur_message_echange',[
                'demandeur' => $demandeur,
                'matricule' => $matricule
            ]);

//            return $this->render('frontend_demandeur/_message_stream.html.twig',[
//                'conversation' => $message,
//            ], new Response('', 200, array('Content-Type' => 'text/vnd.turbo-stream.html')));

        }

        return $this->render('frontend_demandeur/message_echange.html.twig',[
            'demandeur' => $demandeurEntity,
            'form' => $form,
            'conversations' => $this->allRepositories->getConversation($demandeur, $matricule),
            'prestataire' => $prestataire,
            'messages' => $this->allRepositories->getLastMessageByDemandeur($demandeur),
            'active' => $matricule
        ]);
    }
}
