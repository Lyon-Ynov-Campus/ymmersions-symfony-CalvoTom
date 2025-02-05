<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TOURNAMENTRepository;
use App\Repository\MATCHSRepository;
use App\Entity\TOURNAMENT;

final class DisplayController extends AbstractController
{
    #[Route('/display', name: 'app_display')]
    public function index(TOURNAMENTRepository $tournamentRepository): Response
    {
        $tournaments = $tournamentRepository->findAll();
        return $this->render('display/index.html.twig', [
            'controller_name' => 'DisplayController',
            'tournaments' => $tournaments
        ]);
    }

    #[Route('/tournaments/{id}', name: 'app_tournament_view')]
    public function show(int $id, TOURNAMENTRepository $tournamentRepository, MATCHSRepository $matchsRepository): Response
    {
        // Récupérer le tournoi
        $tournament = $tournamentRepository->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException('Tournament not found');
        }

        // Récupérer tous les matchs du tournoi
        $matches = $matchsRepository->findBy(['id_tournament' => $id]);

        // Déterminer le nombre d'équipes max pour calculer les phases
        $nbTeams = $tournament->getNbMaxTeam();
        $totalRounds = log($nbTeams, 2); // Ex: 16 équipes → 4 tours (8e, ¼, ½, finale)

        // Organiser les matchs par phase
        $phases = [
            "Final" => [],
            "Demi-final" => [],
            "Quart de finale" => [],
            "Huitième de finale" => [],
        ];

        // Nombre de matchs restants pour chaque phase
        $remainingMatches = [
            "Huitième de finale" => $nbTeams / 2,
            "Quart de finale" => $nbTeams / 4,
            "Demi-final" => $nbTeams / 8,
            "Final" => 1
        ];

        // Répartir les matchs dans les phases
        foreach ($matches as $match) {
            // Si on est à la phase des huitièmes de finale
            if ($remainingMatches["Huitième de finale"] > 0) {
                $phases["Huitième de finale"][] = $match;
                $remainingMatches["Huitième de finale"]--;
            }
            // Si on est à la phase des quarts de finale
            elseif ($remainingMatches["Quart de finale"] > 0) {
                $phases["Quart de finale"][] = $match;
                $remainingMatches["Quart de finale"]--;
            }
            // Si on est à la phase des demi-finales
            elseif ($remainingMatches["Demi-final"] > 0) {
                $phases["Demi-final"][] = $match;
                $remainingMatches["Demi-final"]--;
            }
            // Si on est à la finale
            elseif ($remainingMatches["Final"] > 0) {
                $phases["Final"][] = $match;
                $remainingMatches["Final"]--;
            }
        }

        return $this->render('display/tournament_view.html.twig', [
            'tournament' => $tournament,
            'phases' => $phases
        ]);
    }
}
