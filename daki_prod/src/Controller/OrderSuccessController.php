<?php

namespace App\Controller;

use App\Classe\cart;
use App\Classe\mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/command/success/{stripSessionId}", name="order_validate")
     */
    public function index(Cart $cart, $stripSessionId)
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripSessionId($stripSessionId);

        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if($order->getState() == 0) {
            //Vider le panier
            $cart->remove();

            //Modifier le statut isPaid à 1
            $order->setState(1);
            $this->entityManager->flush();

            //Envoyer email client confirmation
            $email = new mail();
            $content = "Welcome ".$order->getUser()->getFirstname().".</br><br>Thanks for your order n°".$order->getReference().".</br>";
            $email->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Order success - Daki Suki', $content);
        }

        //Afficher les informations utilisateur de la commande


        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
