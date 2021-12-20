<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueArticleController extends AbstractController
{
    /**
     * @Route("/vue-article/{category}", name="vue_article")
     */
    public function index($category , ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        // 1- je récupére l'objet category corespendant au paramétre avec la méthode findonrBy du repository 
        $categorie = $categoryRepository->findOneBy(['name'=>$category]);
       // 2- je filtre les articles par leurs category 
        $articles = $articleRepository->findBy(['category'=> $categorie]);

        return $this->render('vue_article/vue_article.html.twig', [
          // j'envoi la liste des article filtrés a la vue 
           'articles' => $articles
        ]);
    }
}
