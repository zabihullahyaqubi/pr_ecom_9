<?php


namespace App\Classes;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{
    private $session;
    private $articlerepository;

    public function __construct(SessionInterface $session , ArticleRepository $articleRepository)
    {
        $this->session = $session;
        $this->articlerepository = $articleRepository;
    }

    /**
     * fonction qui ajoute un article au panier 
     */

     public function addPanier($id){
               // je récupére le panier dans la sessions, s"il n'existe pas il sera crée
               $panier=$this->session->get( 'panier' , [] );
               // je test si l'article est déja dans la panier
               if(!empty($panier[$id])){

                // si oui je rajoute 1 a la quantité
                     $panier[$id] = $panier[$id] + 1;
                    

               } else{
                   // si non je créer le nouveau article dans le panier avec 1 comme quantité
                   $panier[$id] = 1;
               }
              // j'enregistre le nouveau panier 
               $this->session->set('panier',$panier);
     }

     /**
      * retourne le panier 
      */

      public function getPanier(){

         return $this->session->get('panier' , []);
      }

      /**
       * supprime le panier 
       */
       public function deltePanier()
       {
          $this->session->remove('panier');

       }

       /**
        * supprime un article du panier
        */

        public function deleteArticlerPanier($id)
        {
            $panier= $this->getPanier();
            if(!empty($panier[$id])){
                unset($panier[$id]);
            }

            $this->session->set('panier',$panier);
        }

        /**
         * soustrait 1 a la quantité, si le reste égale zero , supprime l'article
         */
        public function deleteQuantityArticle($id)
        {
            $panier= $this->getPanier();
            if(!empty($panier[$id])){
               if($panier[$id] > 1 ){
                  $panier[$id] = $panier[$id] - 1 ;
                   // $panier[$id]--;
               }else{
                  unset($panier[$id]);
               }
            }
            $this->session->set('panier',$panier); 
        }

        /**
         * get le dateil des articles dans le panier 
         * fnction qui renvoie un tableau (array)
         */

         public function getDetailPanier():array
         {
             // je récupére le panier 
            $panier= $this->getPanier();
            // je crée un tableau vide 
            $tableau_detail=[];
            // je fait une boucle sur le panier 
            foreach( $panier as $id => $quantity){
                 // je récupére l'article en bdd par la méthode find 
                 $article = $this->articlerepository->find($id);
                 // je test si l'article existe bien on testant ça valeur si null
                 if(!is_null($article)){
                     $prix_ht=$article->getPrix();
                     $tva=$article->getTva()->getTauxTva();
                     // si article n'est pas null , je  rajoute les données au nouveau tableau
                    $tableau_detail[]=[ 
                        'article' => $article,
                        'quantity' => $quantity ,
                        'total_ttc'=> ((($prix_ht * $tva) / 100) + $prix_ht) * $quantity
                    ];
                 }else{
                    // si non je supprime l'id du panier
                    $this->deleteArticlerPanier($id);
                 }
               }
            // je renvoie le nouveau tableau avec uiquement les articles existant en BDD
            return $tableau_detail;
         
         } 

        
             /**
              * get le nombre d'article dans le panier
              */

            public function nbArticlePanier(){

                       $panier= $this->getDetailPanier();

                       return count($panier);

            }

         /**
          * calcule total
          */
          public function calculeTotalPanier2()
          {
            $total=0;
            // je récupére le panier avec les détail
            $panier= $this->getDetailPanier();
           // je calcule le total on faisant une boucle sur le panier 
            foreach( $panier as $row){
                   
                    $total += $row['total_ttc'];
                  }
                  // je retourne le total 
                          return $total;
             }







         



}



