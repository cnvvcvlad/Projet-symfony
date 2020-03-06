<?php

namespace App\Controller;

use App\Entity\Articles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request)
    {
        // Nous récupérons le nom d'hôte depuis l'URL
        $hostname = $request->getSchemeAndHttpHost();
//        dd($hostname);

        // On initialise un tableau pour lister les URLs
        $urls = [];

// On ajoute les URLs " pages statiques du site" au choix
        $urls[] = ['loc' => $this->generateUrl('accueil')];
        $urls[] = ['loc' => $this->generateUrl('app_register')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
//        dd($urls);

// On ajoute les URLs dynamiques des articles dans le tableau
        foreach ($this->getDoctrine()->getRepository(Articles::class)->findAll() as $article) {
            $images = [
                'loc' => '/uploads/images/featured/'.$article->getArtImage(), // URL to image
                'title' => $article->getArtTitle()    // Optional, text describing the image
            ];

            $urls[] = [
                'loc' => $this->generateUrl('actualites_articles', [
                    'slug' => $article->getArtSlug()
                ]),
                'lastmod' => $article->getArtUpdatedAt()->format('Y-m-d'),
                'image' => $images
            ];

        }
//        si on a des videos il faut un autre foreach

//        dd($urls);

        // Fabrication de la réponse XML
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname]),
        // compact('urls', 'hostname')
            200 // le code ok 200 est facultatif à envoyer
        );

// Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

// On envoie la réponse
        return $response;

    }

}
