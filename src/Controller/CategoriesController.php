<?php

namespace App\Controller;

use App\Entity\Categories;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
       $data = $this->getDoctrine()->getRepository(Categories::class)->findBy(
         [], //Where
         ['cat_created_at' => 'desc'] // order by
           // 5 limit
           // 0 offset
       );

        $categories = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            5 // nombre de résultats par page
        );

        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
