<?php

namespace App\Controller\Admin;

use App\Entity\Tva;
use App\Form\TvaType;
use App\Repository\TvaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tva")
 */
class TvaController extends AbstractController
{
    /**
     * @Route("/", name="tva_index", methods={"GET"})
     */
    public function index(TvaRepository $tvaRepository): Response
    {
        return $this->render('tva/index.html.twig', [
            'tvas' => $tvaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tva_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tva = new Tva();
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tva);
            $entityManager->flush();

            return $this->redirectToRoute('tva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tva/new.html.twig', [
            'tva' => $tva,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tva_show", methods={"GET"})
     */
    public function show(Tva $tva): Response
    {
        return $this->render('tva/show.html.twig', [
            'tva' => $tva,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tva_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tva $tva): Response
    {
        $form = $this->createForm(TvaType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tva/edit.html.twig', [
            'tva' => $tva,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tva_delete", methods={"POST"})
     */
    public function delete(Request $request, Tva $tva): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tva->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tva);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tva_index', [], Response::HTTP_SEE_OTHER);
    }
}
