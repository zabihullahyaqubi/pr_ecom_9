<?php

namespace App\Controller\Admin;

use App\Classes\CommandeManager;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande/{livraison}", name="commande" , defaults={ "livraison" : false})
     */
    public function index($livraison,CommandeRepository $commandeRepository): Response
    {

        return $this->render('commande/commande.html.twig', [
            'commandes'=>$commandeRepository->findBy(['status'=> $livraison])
        ]);
    }


    /**
     * @Route("/commande-detail/{id}", name="voir_detail_commande")
     */
    public function voirDetailCommande($id,CommandeRepository $commandeRepository): Response
    { 
        // je récupére l'objet commande par son id passé dans l'url
        $commande=$commandeRepository->find($id);

        return $this->render('commande/detail_commande.html.twig', [
            'commande'=>$commande
        ]);
    }


     /**
     * @Route("/commande-modifier/{id}", name="edit_status_commande")
     */
    public function editStatus($id,CommandeRepository $commandeRepository,
                              CommandeManager $commandeManager): Response
    {
        // je récupére l'objet commande par son id passé dans l'url
        $commande=$commandeRepository->find($id);
        // je change le status de livraison avec la méthode editlivraisoncommande de la class commandemanager
        $commandeManager->editLivraisonCommande($commande);
        // je redirige sur la vue détail on passant lid dans l'url 
        return $this->redirectToRoute('voir_detail_commande',[ 'id' => $id]);


    
    }

}
