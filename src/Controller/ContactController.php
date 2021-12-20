<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact/show", name="contact_index", methods={"GET"})
     */
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    /**
     * @Route("/contact", name="contact_new", methods={"GET","POST"})
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {
        // get data from form
        $formData = $request->request->all();




        // check if method is "post"
        if ($request->isMethod("POST")) {
            $entityManager = $this->getDoctrine()->getManager();
            $contact = new Contact();

            $email = (new TemplatedEmail())
                ->from('fabien@example.com')
                ->to(new Address('zabihullah.yaqubi.2011@gmail.com'))
                ->subject('Thanks for signing up!')

                // path of the Twig template to render
                ->htmlTemplate('email/index.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'nom' => $formData["name"],
                    'userEmail' => $formData["email"],
                    'sujet' => $formData["subject"],
                    'message' => $formData["message"],
                ]);

            $mailer->send($email);

            // save to database
            $contact->setName($formData["name"]);
            $contact->setEmail($formData["email"]);
            $contact->setSubject($formData["subject"]);
            $contact->setMessage($formData["message"]);

            $entityManager->persist($contact);
            $entityManager->flush();

            // redirect
            return $this->redirectToRoute('contact_new');
        }

        return $this->renderForm('contact/new.html.twig');
    }

    /**
     * @Route("/contact/{id}", name="contact_show", methods={"GET"})
     */
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @Route("/contact/{id}/edit", name="contact_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/contact/{id}", name="contact_delete", methods={"POST"})
     */
    public function delete(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_index', [], Response::HTTP_SEE_OTHER);
    }


    public function sendEmail(): Response
    {

    }
}
