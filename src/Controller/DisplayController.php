<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TOURNAMENTRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TOURNAMENT;

final class DisplayController extends AbstractController
{
    #[Route('/display', name: 'app_display')]
    public function index(TOURNAMENTRepository $tournamentRepository): Response
    {
        // Récupère tous les tournois
        $tournaments = $tournamentRepository->findAll();
        return $this->render('display/index.html.twig', [
            'controller_name' => 'DisplayController',
            'tournaments' => $tournaments
        ]);
    }

    #[Route('/tournaments/{id}', name: 'app_tournament_view')]
    public function show(int $id, TOURNAMENTRepository $tournamentRepository): Response
    {
        // Recherche un tournoi par son id
        $tournament = $tournamentRepository->find($id);

        if (!$tournament) {
            // Si aucun tournoi n'est trouvé, rediriger vers la page principale
            throw $this->createNotFoundException('Tournament not found');
        }

        return $this->render('display/tournament_view.html.twig', [
            'tournament' => $tournament
        ]);
    }
}
