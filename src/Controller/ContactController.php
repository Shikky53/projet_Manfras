<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Services\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name : "contact")]
    public function contact(Request $request, MailerService $mailerService)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $emailCustomer = $form->get('emailCustomer')->getData();

            $contenu = $form->get('contenu')->getData();

            $mailerService->sendContactMail($emailCustomer, $contenu);

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact.html.twig',[
            'form' => $form->createView()
        ]);
    }
}

?>