<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */

class ArticleController extends AbstractController

{
   

    /**
     * @Route("/article", name="article")
     */
    public function index(ArticleRepository $articleRepository): Response
    {


        return $this->render('article/article.html.twig', [
            // récupére la liste de tout les articles
          'liste_article' =>$articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/nouveau-article{id}", name="new_article" , defaults={ "id" : null} )
     */
    public function new($id ,Request $request, EntityManagerInterface $manager,ArticleRepository $articleRepository ): Response
    {
        
         // je test le paramétre ID passé dans l'url si null j'ajoute un nouveau article si non je récupére un article existant 
        if (!is_null($id) )
        {
               // récuperation de l'enregistrement a modifier par son id
                $article = $articleRepository->find($id);
                $old_image_name=$article->getImage();
        }else{
               // création d'un objet de la class article
                $article =  new Article();              
        }
       
        // création du formulaire 
        $form= $this->createForm(ArticleType::class,$article);
        // je met le formulaire a l'ecoute de l'objet resquest 
        $form->handleRequest($request);
        // je test si le formulaire dans la vue a était submited 
        if ($form->isSubmitted() && $form->isValid()) {

                // enregistrer l'image 
                //je récupére le fichier (image) qui est passé dans le form
                    $image = $form->get('image')->getData();
                    // je test si l'image a était changée ou une nouvelle est saisie 
                    if(!is_null($image)){
                         // donner un nouveau nom unique a l'image
                         $new_image_name = uniqid() . '.' . $image->guessExtension();
                        // enregistrer l'image sur notre serveur
                        $image->move($this->getParameter('upload_dir'), $new_image_name);
                        // je donne le nom de l'image dans la BDD 
                         $article->setImage($new_image_name);

                    }else{
                        // si y apas de changement d'image je remet l'ancien nom dans la BDD 
                        $article->setImage($old_image_name);
                    }
                   

                      // j'ajoute le nouveau enregistrement
                    $manager->persist($article) ;
                    $manager->flush();
                     // apres insertion je retourne sur la vue qui affiche les articles 
                    return $this->redirectToRoute('article');

        }

        return $this->render('article/new_article.html.twig', [
             // je passe le formulaire a la vue 
                'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/supprimer-article{id}" , name="delete_article"  )
     */
    public function delete($id, EntityManagerInterface $manager,ArticleRepository $articleRepository){
         // je récupére l'article par son id passé dans l'url

             $article=$articleRepository->find($id);
             // je met a jour 
             $manager->remove($article);
             $manager->flush();

             return $this->redirectToRoute('article');

    }

    /**
     * @Route("/voir-article/{id}" , name="show_article"  )
     */

     public function show($id,ArticleRepository $articleRepository){
                 // je récupére l'article par son id passé dans l'url
                $article= $articleRepository->find($id);

                return $this->render('article/vue_article.html.twig',[
                    // j'envoi l'article a la vue 
                    'article'=>$article
                ]);
     }
   


}
