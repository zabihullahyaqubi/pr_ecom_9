<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classes\Panier;

class PanierController extends AbstractController
{
    /**
     * méthode qui affiche la vue du panier 
     * @Route("/panier", name="panier")
     */
    public function index(Panier $panier): Response
    {
       $user= $this->getUser();
       if(!$user){
           return $this->redirectToRoute('app_login');
       }
       // dd($panier->getDetailPanier());
        return $this->render('panier/panier.html.twig', [
            // j'envoi a la vue le détail du panier
          'panier' => $panier->getDetailPanier(),
          // j'envoi a la vue le total du panier 
          'total' =>$panier->calculeTotalPanier2(),
          'bn_article' =>$panier->nbArticlePanier()
         
        ]);
    }



     /**
      * méthode qui ajoute 1 article au panier
     * @Route("/ajouter-panier/{id}", name="add_panier")
     */
    public function addArticlePanier($id,Panier $panier): Response
    {
        
        $panier->addPanier($id);
        return $this->redirectToRoute('panier');
    }
    
     /**
     * @Route("/effacer-panier", name="delete_panier")
     */
    public function deletePanier(Panier $panier): Response
    {
        
        $panier->deltePanier();
        return $this->redirectToRoute('panier');
    }
    
     /**
     * @Route("/effacer-article-panier/{id}", name="delete_article_panier")
     */
    public function deletearticlePanier($id,Panier $panier): Response
    {
        
        $panier->deleteArticlerPanier($id);
        return $this->redirectToRoute('panier');
    }
    
      /**
     * @Route("/enleve-quantite-panier/{id}", name="delete_quantite_panier")
     */
    public function deleteQuantitePanier($id,Panier $panier): Response
    {
        
        $panier->deleteQuantityArticle($id);
        return $this->redirectToRoute('panier');
    }



}
