<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programsFantastique = $programRepository->programCategory('Fantastique');
        $programsAction = $programRepository->programCategory('Action');
        $programsAnimation = $programRepository->programCategory('Animation');
        $programsSf = $programRepository->programCategory('Science-Fiction');

        return $this->render('home/index.html.twig', [
            'programsAction' => $programsAction,
            'programsFantastique' => $programsFantastique,
            'programsAnimation' => $programsAnimation,
            'programsSf' => $programsSf
        ]);
    }
}
