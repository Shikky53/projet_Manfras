<?php

namespace App\Controller;

use App\Form\PasswordLostType;
use App\Repository\UserRepository;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class LostPasswordController extends AbstractController
{
    #[Route('/lost/password', name: 'lost_password')]
    public function index(TokenGeneratorInterface $tokenGenerator, 
    UserRepository $userRepository, 
    EntityManagerInterface $em, Request $request, MailerService $mailer): Response
    {

        $form = $this->createForm(PasswordLostType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $email = $form->get('email')->getData();

            $user = $userRepository->findOneBy([
                'email' => $email
            ]);

            if(!$user)
            {
                return $this->redirectToRoute('app_login');
            }

            $token = $tokenGenerator->generateToken() . uniqid();

            $user->setTokenPasswordLost($token);

            $em->flush();

            $mailer->sendLostPasswordEmail($user);

            return $this->redirectToRoute('app_login');
        }


        return $this->render('lost_password/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
