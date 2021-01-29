<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Content;
use App\Entity\Navigation;
use App\Entity\Vote;
use App\Form\ContactType;
use App\Repository\ContentRepository;
use App\Repository\LicensePlateRepository;
use App\Repository\NavigationRepository;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
     * @Route("/liste-{navigationShortText}", name="app_contents")
     */
    public function contents(NavigationRepository $navigationRepository, string $navigationShortText): Response
    {
        $navigation = $navigationRepository->findOneBy(['shortText' => $navigationShortText]);
        $contents = $navigation->getContents();
        return $this->render('frontend/contents.html.twig', [
            'navigations' => $navigationRepository->findBy([], ['position' => 'ASC']),
            'navigation' => $navigation,
            'contents' => $contents,
        ]);
    }

    /**
     * @Route("/{contentShortText}", name="content_show", methods={"GET"})
     */
    public function show(
        string $contentShortText,
        ContentRepository $contentRepository,
        NavigationRepository $navigationRepository,
        VoteRepository $voteRepository
    ): Response {
        $content = $contentRepository->findOneBy(['shortText' => $contentShortText]);
        $canVote = true;
        $ipAddress = $this->getUserIpAddr();
        $vote = $voteRepository->findOneBy(['content' => $content, 'ipAdress' => $ipAddress]);
        if (is_object($vote)) {
            $canVote = false;
        }
        return $this->render('content/show.html.twig', [
            'content' => $content,
            'canVote' => $canVote,
            'navigations' => $navigationRepository->findBy([], ['position' => 'ASC']),
        ]);
    }

    /**
     * @Route("/contact", name="contact_new", methods={"GET","POST"})
     */
    public function new(Request $request, NavigationRepository $navigationRepository, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Ihr Kontaktanfrage wurde erfolgreich verschickt!');

            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
        }

        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'navigations' => $navigationRepository->findBy([], ['position' => 'ASC']),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/content/license_plate", name="content_license_plate")
     */
    public function getLicensePlate(Request $request, LicensePlateRepository $licensePlateRepository): JsonResponse
    {
        $licensePlateString = $request->request->get('licensePlate');
        $licensePlate = $licensePlateRepository->findOneBy(['shortText' => $licensePlateString]);
        if (null === $licensePlate || !is_object($licensePlate)) {
            return new JsonResponse('Zu diesem Kennzeichen gibt es kein Ergebnis');
        }
        return new JsonResponse($licensePlate->getText());
    }

    /**
     * @Route("/content/positive_update", name="content_positive_update")
     */
    public function updatePositive(Request $request, ContentRepository $contentRepository): JsonResponse
    {
        $contentId = $request->request->get('content');
        $content = $contentRepository->findOneBy(['id' => $contentId]);
        $positive = $content->getPositive();
        $positive++;
        $content->setPositive($positive);
        $vote = new Vote();
        $vote->setContent($content);
        $vote->setIpAdress($this->getUserIpAddr());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($vote);
        $entityManager->persist($content);
        $entityManager->flush();

        return new JsonResponse($positive);
    }

    /**
     * @Route("/content/negative_update", name="content_negative_update")
     */
    public function updateNegative(Request $request, ContentRepository $contentRepository): JsonResponse
    {
        $contentId = $request->request->get('content');
        $content = $contentRepository->findOneBy(['id' => $contentId]);
        $negative = $content->getNegative();
        $negative++;
        $content->setNegative($negative);
        $vote = new Vote();
        $vote->setContent($content);
        $vote->setIpAdress($this->getUserIpAddr());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($vote);
        $entityManager->persist($content);
        $entityManager->flush();

        return new JsonResponse($negative);
    }

    /**
     * @return mixed
     */
    private function getUserIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}
