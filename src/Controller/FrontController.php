<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('front/index.html.twig', [
            'variable' => 'C\'est une variable cliquable'
        ]);
    }

    /**
     * @return mixed
     * @Route("/mentions_legales", name="mentions_legales")
     */
    public function mentionsLegales()
    {
        // génère la vue de la page des mentions légales
        return $this->render('front/mentions-legales.html.twig');
    }

    /**
     * @return mixed
     * @Route("/home", name="home")
     */
    public function home()
    {
        return $this->render('front/home.html.twig', [
            'title' => 'Bienvenue sur la page d\'accueil de notre blog! On vous souhaite une agréable lecture!',
            'description' => 'Le blog du ChessTeam Nogent sur Marne propose aux internautes passionés des échecs  de consulter ses articles publiés, s\'inscrire en tant que membre pour publier ses propres articles et commentaires'
        ]);
    }

    public function conditions()
    {
        return $this->render('front/conditions.html.twig');
    }

    public function __invoke()
    {
        return $this->render('front/questions.html.twig');
    }
}
