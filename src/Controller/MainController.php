<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {
        $tricks = $this->getDoctrine()->getRepository(Trick::class)->findAll();

        return $this->render('main/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
