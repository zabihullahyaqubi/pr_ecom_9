<?php


namespace App\Classes;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Entity\User;

use DateTime;

class CommandeManager
{

   private $security;
   private $manager;


   public function __construct(Security $security,EntityManagerInterface $manager)
   {
       $this->security = $security;
       $this->manager = $manager;
   }


   /**
    * function pour créer une commande ( rajouter un enregistrement a la table commande)
    */
   public function setCommande(Panier $panier,User $user){

          // je crée un objet commande 
          $commande = new Commande();
          // je récupére le user connécté
         
        
          // je rentre le nom de l'user connecté
          $commande->setUser($user);
          // je récupére la date du jour
          $date_commande = new DateTime();
          $commande->setDateCommande($date_commande);
          // je récupére l'adresse de l'user
           $adresse= $user->getAdresse();
           //j'ajoute l'adresse a la commande
           $commande->setAdresse($adresse);
           // ajouter un total
           $total= $panier->calculeTotalPanier2();
           $commande->setTotal($total);

           return $commande;



   }

   /**
    * créeun objet détail panier puis l'implemente avec les ligne du panier 
    */
  
    public function set_detail_panier($ligne_panier,$commande){

           // je crée un objet detail_commande de la classe DetailCommande
           $detail_commande= new DetailCommande();
           // je set la relation avec la commande
           $detail_commande->setCommande($commande);
           // je récupére le nom de l'article de la ligne du panier
           $name_de_article=$ligne_panier['article']->getName();
           // je set le nom de l'article dans l'objet detail commande
           $detail_commande->setArticleName($name_de_article);
           // je récupére la refde l'article de la ligne du panier
           $ref = $ligne_panier['article']->getRef();
           // je set la ref de l'article dans l'objet detail commande
           $detail_commande->setRef($ref);
            // je récupére la quantity  dans la ligne du panier
           $quantity=$ligne_panier['quantity'];
           // je set la quantity  dans l'objet detail commande
           $detail_commande->setQuantity($quantity);
            // je récupére le prix de l'article de la ligne du panier
           $prix= $ligne_panier['total_ttc'];
           // je set le prix de l'article dans l'objet detail commande
           $detail_commande->setPrix($prix);

      
           return $detail_commande;


    }

    /**
     * modifiele statu de livraison
     */

     public function editLivraisonCommande(Commande $commande){

           $commande->setStatus(true);
           $this->manager->persist($commande);
           $this->manager->flush();
     }








}