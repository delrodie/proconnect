<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use App\Security\LoginRedirectPath;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class RegistrationController extends AbstractController
{
    public function __construct(
        private Utilities $utilities,
        private LoginRedirectPath $loginRedirectPath
    )
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles([$this->utilities->getUserRole($user->getStatut())]);
            $user->setStatut($user->getStatut());

            $entityManager->persist($user);
            $entityManager->flush();

            notyf()->success("Votre compte '{$user->getUserIdentifier()}' a été crée avec succès");
            notyf()->info("Vous êtes invité.e à finaliser votre inscription en renseignant votre profile complet");

            $this->loginRedirectPath->getRedirectUrlBaseOnRole($user->getStatut());

            return $security->login($user, LoginAuthenticator::class, 'main');

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
