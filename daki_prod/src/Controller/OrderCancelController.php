<?php

namespace App\Controller;

use App\Classe\mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/command/error/{stripSessionId}", name="order_cancel")
     */
    public function index($stripSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripSessionId($stripSessionId);

        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        //Envoyer mail utilisateur pour indiquer échec de paiement
        $email = new mail();
        $content = "Welcome ".$order->getUser()->getFirstname().".</br><br>We apologize but your order is canceled.<br>";
        $email->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Order success - Daki Suki', $content);

        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}
