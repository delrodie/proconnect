<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Entity\Message;
use App\Form\MessageFormType;
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

#[Route('/{prestataire}-messages')]
#[isGranted('ROLE_PRESTATAIRE')]
class PrestataireMessageController extends AbstractController
{
    use TargetPathTrait;
    public function __construct(
        private AllRepositories $allRepositories,
        private Utilities $utilities,
        private EntityManagerInterface $entityManager,
    )
    {
    }
    #[Route('/', name: 'app_frontend_prestataire_message_list')]
    public function list($prestataire): Response
    {
        return $this->render('frontend_prestataire/message_list.html.twig',[
            'prestataire' => $this->allRepositories->getOnePrestataire($prestataire),
            'messages' => $this->allRepositories->getLastMessageByPrestataire($prestataire)
        ]);
    }

    #[Route('/{code}', name: 'app_frontend_prestataire_message_echange',methods: ['GET','POST'])]
    public function echange(Request $request, $prestataire, $code)
    {
        // Verification du demandeur
        $demandeur = $this->allRepositories->getOneDemandeur($code);
        if (!$demandeur){
            sweetalert()->error(Messages::DEMANDEUR_NOT_EXIST,[],'ECHEC');

            return $this->redirectToRoute('app_frontend_prestataire_message_list',['prestataire' => $prestataire]);
        }

        // Verification du prestataire
        $prestataireEntity = $this->allRepositories->getOnePrestataire(null, $this->getUser());
        if (!$prestataireEntity || $prestataireEntity->getMatricule() !== $prestataire){
            sweetalert()->error(Messages::PRESTATAIRE_NOT_YOU,[],'ATTENTION');

            return $this->redirectToRoute('app_frontend_prestataire_message_list',['prestataire' => $prestataire]);
        }

        // Nouveau message
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $message->setReference(time());
            $message->setEmetteur(Messages::EMETTEUR_PRESTATAIRE);
            $message->setCreatedAt($this->utilities->fuseauGMT());
            $message->setPrestataire($prestataireEntity);
            $message->setDemandeur($demandeur);
            $message->setVue(false);

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_frontend_prestataire_message_echange',[
                'code' => $code,
                'prestataire' => $prestataire
            ]);
        }

        return $this->render('frontend_prestataire/message_echange.html.twig',[
            'messages' => $this->allRepositories->getLastMessageByPrestataire($prestataire),
            'conversations' => $this->allRepositories->getConversation($code, $prestataire),
            'prestataire' => $prestataireEntity,
            'form' => $form,
            'active' => $code,
            'demandeur' => $demandeur
        ]);

    }
}
