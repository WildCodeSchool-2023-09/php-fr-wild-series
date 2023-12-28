<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/{id}', name:'show')]
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program
            ]);
    }
    #[Route('/{id}/seasons', name:'show_seasons')]
    public function showSeasons(Program $program): Response
    {
        return $this->render('program/show_seasons.html.twig', [
            'program' => $program
        ]);
    }
    #[Route('/{program}/season/{season}/episodes', name: 'episode_show')]
    public function showEpisode(Program $program, Season $season): Response
    {
        return $this->render('program/show_episodes.html.twig', [
            'program' => $program,
            'season' => $season
        ]);
    }

    #[Route('/{program}/actors', name: 'actor_show')]
    public function showActors(Program $program): Response
    {
        return $this->render('program/actor_show.html.twig', [
            'program' => $program
        ]);
    }
    #[Route('/{program}/comments', name: 'comment_show')]
    public function showComments(Program $program): Response
    {
        return $this->render('program/comment_show.html.twig', [
            'program' => $program
        ]);
    }
}
