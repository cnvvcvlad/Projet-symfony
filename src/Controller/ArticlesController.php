<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Entity\Comments;
use Symfony\Component\HttpFoundation\Request; // Nous avons besoin d'accéder à la requête pour obtenir le numéro de page
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

/**
 * Class ArticlesController
 * @package App\Controller
 * @Route("/actualites", name="actualites_")
 */

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
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
     * @Route("/{slug}", name="article")
     */
    public function showArticle($slug)
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

        // Si l'article existe nous envoyons les données à la vue
        return $this->render('articles/article.html.twig', compact('article','commentaires'));
    }

}
