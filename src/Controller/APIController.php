<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Users;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api", name="api_")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/", name="api")
     */
    public function index()
    {

        // vide
    }

    /**
     * @Route("/articles/liste", name="liste", methods={"GET"})
     */
    public function liste(ArticlesRepository $articlesRepo)
    {
//        $articles = $articleRepo->findAll();
//        dd($articles); // en ojet PHP

//        return $this->json($articlesRepo->apiFindAll(), 200, [], ['groups' => 'article:read']); // en json avec notre methode

        return $this->json($articlesRepo->findAll(), 200, [], ['groups' => 'article:read']); // en json

    }

    /**
     * @Route("/article/lire/{id}", name="article", methods={"GET"})
     */
    public function getArticle($id, ArticlesRepository $articleRepo)
    {
        return $this->json($articleRepo->findOneBy(['id' => $id]), 200, [], ['groups' => 'article:read']); // en json

    }


    /**
     * @Route("/article/ajout", name="ajout", methods={"POST"})
     */
    public function addArticle(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $jsonRecu = $request->getContent();

        try {

            $article = $serializer->deserialize($jsonRecu, Articles::class, 'json');

            // On hydrate l'objet

            $article->setArtCreatedAt(new \DateTime());
            // mettre en place une athentification, token user
            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(["id" => 1]); // utilisateur id =  1 par défaut
            $article->setUser($user);

            // on valide les données
            $errors = $validator->validate($article);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            // On sauvegarde en base de données
            $entityManager->persist($article);
            $entityManager->flush();

//            dd($article);

            return $this->json($article, 201, [], ['groups' => 'post:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

    }


    /**
     * @Route("/article/editer/{id}", name="edit", methods={"PUT"})
     */
    public function editArticle(?Articles $article, Request $request)
    //on a mis le '?' pour que l'article soit prise en compte meme s'il est vide
    {

        // On vérifie si la requête est une requête Ajax
//        if ($request->isXmlHttpRequest()) {

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());

            // On initialise le code de réponse
            $code = 200;

            // Si l'article n'est pas trouvé
            if (!$article) {
                // On instancie un nouvel article
                $article = new Articles();
                // On change le code de réponse
                $code = 201;
            }

// On hydrate l'objet
            $article->setArtTitle($donnees->art_title);
            $article->setArtSlug($donnees->art_slug);
            $article->setArtDescription($donnees->art_description);
            $article->setArtContent($donnees->art_content);
            $article->setArtImage($donnees->art_image);
            $article->setArtUpdatedAt(new \DateTime());
            $user = $this->getDoctrine()->getRepository(Users::class)->find(1);
            $article->setUser($user);

// On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

// On retourne la confirmation
            return new Response('ok', $code);
//        }
//        return new Response('Failed', 404);
    }


    /**
     * @Route("/article/supprimer/{id}", name="supprime", methods={"DELETE"})
     */
    public function removeArticle(Articles $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return new Response('ok'); // code 200 par défaut
    }


}
