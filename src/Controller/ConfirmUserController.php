<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfirmUserController extends AbstractController
{
    #[Route('/confirmationuser/{token}', name: 'app_confirm_email')]
    public function confirmUser(string $token, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $user = $userRepository->findOneBy([
            'tokenConfirmationEmail' => $token
        ]);

        if($user)
        {
            $user->setIsConfirmed(true);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->redirectToRoute('home');

    }
}

?>