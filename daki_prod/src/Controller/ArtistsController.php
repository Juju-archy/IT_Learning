<?php

namespace App\Controller;

use App\Entity\Artists;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/artists", name="artists")
     */
    public function index(): Response
    {
        $artists = $this->entityManager->getRepository(Artists::class)->findAll();

        return $this->render('artists/index.html.twig', [
            'artists' => $artists
        ]);
    }
}
