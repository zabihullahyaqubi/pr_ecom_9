<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ArticleRepository $articleRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    // j'injecte les services (ici les repository ) ArticleRepository et CategoryRepository dans les parenthéses pour pouvoir accéder a leurs méthodes
    public function index(ArticleRepository $articleRepository , CategoryRepository $categoryRepository): Response
    {

        return $this->render('home/index.html.twig', [
            // j'envoi la liste des articles récupérer par la méthode findAll de ArticleRepository
           'liste_article'=>$articleRepository->findAll(),
           // j'envoi la liste des category récupérer par la méthode findAll de ArticleCategory 
           'liste_category'=>$categoryRepository->findAll()
        ]);
    }


    /**
     * @Route("/voir-article-detail/{id}" , name="show_article_user"  )
     */

    public function show($id,ArticleRepository $articleRepository){
        // je récupére l'article par son id passé dans l'url
       $article= $articleRepository->find($id);

       return $this->render('home/vue_article_user.html.twig',[
           // j'envoi l'article a la vue 
           'article'=>$article
       ]);
    }


}
