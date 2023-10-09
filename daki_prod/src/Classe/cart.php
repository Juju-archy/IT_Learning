<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class cart
{
    private $session;
    private $entityManager;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function add(int $id)
    {
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function decrease(int $id)
    {
        $cart = $this->session->get('cart', []);

        if($cart[$id]>1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }

    public function getFull()
    {
        $cartComplete = [];

        if($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $productObject = $this->entityManager->getRepository(Product::class)->findOneById($id);
                //Security : test the ID if exist
                if(!$productObject) {
                    $this->delete($id);
                    continue;
                }

                $cartComplete [] = [
                    'product' => $productObject,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }
}