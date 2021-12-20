<?php

namespace App\Controller;

use App\Classes\CommandeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classes\Panier;
use Doctrine\ORM\EntityManagerInterface;

class AchatController extends AbstractController
{
    /**
     * @Route("/achat", name="achat")
     */
    public function index(Panier $panier,CommandeManager $commandeManager, EntityManagerInterface $manager): Response
    {   
       

        // je récupére le user connécté
        $user = $this->getUser();

        // j'appéle la méthode setcommande pour ajouter la nouvelle commande a la bdd
        $commande = $commandeManager->setCommande($panier,$user);
        // je persiste sans flusher
        $manager->persist($commande) ;
        // je récupére les datil du panier
        $detail_panier=$panier->getDetailPanier();
        // je fait une boucle sur le tableau des détails du panier 
        foreach( $detail_panier as $ligne_panier){
            // pour chaque ligne du tableau des détails ,je la passe a la léthode set_detail_panier
               $detail_commande = $commandeManager->set_detail_panier($ligne_panier,$commande);

               $manager->persist($detail_commande);

        }

        // je flush pour toutes les persiste passé avant 
        $manager->flush();

        $panier->deltePanier();
         return $this->render('achat/achat.html.twig', [
          'commande' => $commande
        ]);
    }
}
