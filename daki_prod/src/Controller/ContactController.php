<?php

namespace App\Controller;

use App\Classe\mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', 'Thanks for your message.');

            $contactFormData = $form->getData();

            //Envoyer mail
            $email = new mail();
            $email->send('contact@daki-suki.com', 'Formulaire de contact', 'Formulaire de contact : '.$contactFormData['lastname'], 'Bonjour,<br/>Vous avez reçu un message de '.$contactFormData['lastname'].' '.$contactFormData['firstname'].'<br/>Mail: '.$contactFormData['email'].'<br/>Message: "'.$contactFormData['content'].'"');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
