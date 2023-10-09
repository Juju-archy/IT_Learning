<?php

namespace App\Controller;

use App\Classe\cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function index(Cart $cart)
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route ("/my-cart/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, int $id)
    {
        $cart->add($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route ("/my-cart/remove", name="remove_by_cart")
     */
    public function remove(Cart $cart)
    {
        $cart->remove();

        return $this->redirectToRoute('product');
    }

    /**
     * @Route ("/my-cart/delete/{id}", name="delete_to_cart")
     */
    public function delete(Cart $cart, int $id)
    {
        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route ("/my-cart/decrease/{id}", name="decrease_product")
     */
    public function decrease(Cart $cart, int $id)
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}
