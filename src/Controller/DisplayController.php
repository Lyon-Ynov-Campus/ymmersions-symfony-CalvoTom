<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TOURNAMENTRepository;
use App\Repository\MATCHSRepository;
use App\Entity\MATCHS;
use App\Repository\REGISTERRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function show(
        int $id, 
        TOURNAMENTRepository $tournamentRepository, 
        MATCHSRepository $matchsRepository, 
        REGISTERRepository $registerRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer le tourno
        $tournament = $tournamentRepository->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException('Tournament not found');
        }

        $currentDate = new \DateTime();
        
        // Vérifier si le tournoi a commencé
        $hasStarted = $currentDate >= $tournament->getDateStart();

        if ($hasStarted) {
            // Récupérer tous les matchs du tournoi
            $matches = $matchsRepository->findBy(['id_tournament' => $id]);
            if ($matches ){
                $this->generateRoundMatches($tournament, $registerRepository, $entityManager);
            }

            // Déterminer le nombre d'équipes max pour calculer les phases
            $nbTeams = $tournament->getNbMaxTeam();
            $totalRounds = log($nbTeams, 2);

            // Organiser les matchs par phase
            $phases = [
                "Final" => [],
                "Demi-final" => [],
                "Quart de finale" => [],
                "Huitième de finale" => [],
            ];

            $remainingMatches = [
                "Huitième de finale" => $nbTeams / 2,
                "Quart de finale" => $nbTeams / 4,
                "Demi-final" => $nbTeams / 8,
                "Final" => 1
            ];

            if ($nbTeams == 8) {
                $remainingMatches["Huitième de finale"] = 0;
                $remainingMatches["Quart de finale"] = 4;
            } elseif ($nbTeams == 4) {
                $remainingMatches["Huitième de finale"] = 0;
                $remainingMatches["Quart de finale"] = 0;
                $remainingMatches["Demi-final"] = 2;
            } elseif ($nbTeams == 2) {
                $remainingMatches["Huitième de finale"] = 0;
                $remainingMatches["Quart de finale"] = 0;
                $remainingMatches["Demi-final"] = 0;
                $remainingMatches["Final"] = 1;
            }

            foreach ($matches as $match) {
                if ($remainingMatches["Huitième de finale"] > 0) {
                    $phases["Huitième de finale"][] = $match;
                    $remainingMatches["Huitième de finale"]--;
                } elseif ($remainingMatches["Quart de finale"] > 0) {
                    $phases["Quart de finale"][] = $match;
                    $remainingMatches["Quart de finale"]--;
                } elseif ($remainingMatches["Demi-final"] > 0) {
                    $phases["Demi-final"][] = $match;
                    $remainingMatches["Demi-final"]--;
                } elseif ($remainingMatches["Final"] > 0) {
                    $phases["Final"][] = $match;
                    $remainingMatches["Final"]--;
                }
            }

            return $this->render('display/tournament_view.html.twig', [
                'tournament' => $tournament,
                'phases' => $phases
            ]);
        } else {
            // Le tournoi n'a pas encore commencé, afficher les équipes inscrites
            $registers = $registerRepository->findBy(['id_tournament' => $tournament]);
            $teams = [];

            foreach ($registers as $register) {
                $teams[] = $register->getIdTeam();
            }

            return $this->render('display/tournament_view.html.twig', [
                'tournament' => $tournament,
                'teams' => $teams,
                'hasStarted' => $hasStarted
            ]);
        }
    }

    public function generateRoundMatches(TOURNAMENT $tournament, REGISTERRepository $registerRepository, EntityManagerInterface $entityManager): void
    {
        $registers = $registerRepository->findBy(['id_tournament' => $tournament]);
        $teams = [];

        foreach ($registers as $register) {
            $teams[] = $register->getIdTeam();
        }

        if (count($teams) % 2 !== 0) {
            throw $this->createNotFoundException('Le nombre d\'équipes doit être pair pour générer les matchs.');
        }

        $roundMatches = [];

        for ($i = 0; $i < count($teams); $i += 2) {
            $team1 = $teams[$i];
            $team2 = $teams[$i + 1];

            $match = new MATCHS();
            $match->setIdTournament($tournament);
            $match->setIdTeam1($team1);
            $match->setIdTeam2($team2);
            $match->setDate(new \DateTime());
            
            $entityManager->persist($match);
            $roundMatches[] = $match;
        }

        $entityManager->flush();
    }

}
