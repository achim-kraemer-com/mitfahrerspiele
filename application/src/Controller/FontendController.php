<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Content;
use App\Entity\Navigation;
use App\Form\ContactType;
use App\Repository\NavigationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FontendController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="app_index")
     */
    public function index(NavigationRepository $navigationRepository): Response
    {
        return $this->render('frontend/index.html.twig', [
            'navigations' => $navigationRepository->findBy([], ['position' => 'ASC']),
        ]);

    }

    /**
     * @Route("/imprint", name="app_imprint")
     */
    public function imprint(NavigationRepository $navigationRepository): Response
    {
        return $this->render('imprint.html.twig', [
            'navigations' => $navigationRepository->findBy([], ['position' => 'ASC']),
        ]);
    }

    /**
     * @Route("/contents/{navigation}", name="app_contents")
     */
    public function contents(Navigation $navigation, NavigationRepository $navigationRepository): Response
    {
        $contents = $navigation->getContents();
        return $this->render('frontend/contents.html.twig', [
            'navigations' => $navigationRepository->findBy([], ['position' => 'ASC']),
            'navigation' => $navigation,
            'contents' => $contents,
        ]);
    }

    /**
     * @Route("content/{id}", name="content_show", methods={"GET"})
     */
    public function show(Content $content): Response
    {
        return $this->render('content/show.html.twig', [
            'content' => $content,
        ]);
    }

    /**
     * @Route("/contact", name="contact_new", methods={"GET","POST"})
     */
    public function new(Request $request, NavigationRepository $navigationRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
        }

        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'navigations' => $navigationRepository->findBy([], ['position' => 'ASC']),
            'form' => $form->createView(),
        ]);
    }
}
