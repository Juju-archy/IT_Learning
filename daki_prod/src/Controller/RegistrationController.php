<?php

namespace App\Controller;

use App\Classe\mail;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if(!$search_email) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();


                //Send a confirmation email
                $email = new mail();
                $content = "Welcome ".$user->getFirstname().".</br>Your can now visit our website.</br>";
                $email->send($user->getEmail(), $user->getFirstname(), 'Welcome on Daki Suki', $content);

                //Notification
                $notification = "Your registration is a success. You can now connect to your account.";

                return $this->redirectToRoute('home');
            } else {
                $notification = "There is already an account with this email";
            }

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
