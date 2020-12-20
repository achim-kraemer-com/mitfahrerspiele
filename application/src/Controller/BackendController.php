<?php

namespace App\Controller;

use App\Repository\NavigationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/backend", name="backend_index")
     */
    public function index(NavigationRepository $navigationRepository)
    {
        return $this->render('backend/index.html.twig', [
            'navigations' => $navigationRepository->findBy([], ['position' => 'ASC']),
        ]);
    }
}
