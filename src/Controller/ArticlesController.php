<?php

namespace App\Controller;

use App\Entity\MotsCles;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Entity\Comments;
use App\Form\CommentsFormType;
use App\Form\AddArticleFormType;
use Symfony\Component\HttpFoundation\Request; // Nous avons besoin d'accéder à la requête pour obtenir le numéro de page
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ArticlesController
 * @package App\Controller
 * @Route("/actualites", name="actualites_")
 */

class ArticlesController extends AbstractController
{


    /**
     * @Route("/", name="articles")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        // Méthode findBy qui permet de récupérer les données (articles) avec des critères de filtre et de tri
        $data = $this->getDoctrine()->getRepository(Articles::class)->findBy(
            [], //WHERE
            ['art_created_at' => 'desc'] //ORDER BY
             // 5 LIMIT
            // 0 OFFSET
        );

        $articles = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );


        return $this->render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @IsGranted("ROLE_USER") // seul les éditeurs et les personnes hierarchiquement plus haute (admins) peuvent accéder à cette route
     * @Route("/articles/new", name="ajout_article")
     */
    public function addArticle(Request $request)
    {
        //on instancie l'objet article
        $article = new Articles();
        //on instancie l'objet mot cle
        $mot_cle = new MotsCles();

        //on crée l'objet formulaire avec les 3 parametres : type du formulaire, les données de l'objet, les options éventuelles
        $form = $this->createForm(AddArticleFormType::class, $article);

        //on récupère les donnés saisies
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mot_cle->setMotCle($request);
            $mot_cle->setMotCleSlug($request);
            $article->setArtCreatedAt(new \DateTime());
            $article->setUser($this->getUser());

            // on enregistre les informations dans la base de données pour l'article que vient d'etre créé
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
        }

        //on renvoie le formulaire avec les parametres à la vue
        return $this->render('articles/add.html.twig', [
            'articleForm' => $form->createView()
        ]);

    }


    /**
     * @Route("/articles/{slug}", name="article")
     */
    public function showArticle($slug, Request $request)
    {
        // On récupère l'article correspondant au slug
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['art_slug' => $slug]);

        // On récupére les commentaires actifs de l'article
        $commentaires = $this->getDoctrine()->getRepository(Comments::class)->findBy([
            'article' => $article,
            'actif' => 1
        ],['com_created_at' => 'desc']);

        if(!$article){
            // Si aucun article n'est trouvé, nous créons une exception
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        //on instancie l'entité Comments qui va contenir les données du comentaire
        $comment = new Comments();

        $user = new Users();

        //on crée l'objet formulaire avec les 3 parametres : type, les données, les options
        $form = $this->createForm(CommentsFormType::class, $comment);

        //on récupère les donnés saisies
        $form->handleRequest($request);

        //on vérifie si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Hydrate notre commentaire avec l'article
            $comment->setArticle($article);
            // Hydrate notre commentaire avec la date et l'heure courants
            $comment->setComCreatedAt(new \DateTime('now'));

            // Hydrate notre commentaire avec l'email de l'utilisateur conecté
            $comment->setEmailAuthor($this->getUser()->getEmail($user));

            // Hydrate notre commentaire avec l'id de l'auteur
            $comment->setUser($this->getUser());


            //on instancie Doctrine
            $doctrine = $this->getDoctrine()->getManager();

            // On hydrate notre instance $comment
            $doctrine->persist($comment);

            // On écrit en base de données
            $doctrine->flush();
        }


        // Si l'article existe nous envoyons les données à la vue
//        return $this->render('articles/article.html.twig', compact('article','commentaires'));

        // lorsque nous avons différentes valeurs à envoyer (en raison de l'ajout du "createView" pour le formulaire)  on utilise le tableau associatif de données et pas la méthode compact


        return $this->render('articles/article.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires,
            'commentForm' => $form->createView()
        ]);

    }



}
