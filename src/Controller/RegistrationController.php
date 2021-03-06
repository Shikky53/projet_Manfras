<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\HandleImage;
use App\Services\MailerService;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, 
    UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $entityManager, 
    HandleImage $handleImage,
    TokenGeneratorInterface $tokenGenerator, MailerService $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

              //Recuperer le fichier 
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            //Verifier que il y a bien un fichier
            if ($file) {
                $handleImage->save($file, $user);
            }

            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $token = $tokenGenerator->generateToken() . uniqid();

            $user->setTokenConfirmationEmail($token);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $mailer->sendConfirmationEmail($user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
