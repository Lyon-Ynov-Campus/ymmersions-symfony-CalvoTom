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
use App\Entity\TEAM;

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
        $tournament = $tournamentRepository->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException('Tournament not found');
        }

        $currentDate = new \DateTime();
        
        $hasStarted = $currentDate >= $tournament->getDateStart();

        if ($hasStarted) {
            $nbTeams = $tournament->getNbMaxTeam();
            $totalRounds = log($nbTeams, 2);
            $currentRound = 1;

            
            $matchesByPhase = [];
            $allMatches = $matchsRepository->findBy(['id_tournament' => $id]);

            
            if (empty($allMatches)) {
                
                $this->generateRoundMatches($tournament, $registerRepository, $entityManager);

                
                $matchesByPhase = $this->getMatchesByPhase($tournament, $matchsRepository);
            } else {
                
                for ($round = 1; $round <= $totalRounds; $round++) {
                    $matchesInCurrentRound = $matchsRepository->findBy([
                        'id_tournament' => $id,
                        'phase' => $round
                    ]);
                    if (!empty($matchesInCurrentRound)) {
                        $matchesByPhase[$round] = [];
            
                        foreach ($matchesInCurrentRound as $match) {
                            $matchesByPhase[$round][] = $match->getId(); 
                        }
                    }
                }
            }

            return $this->render('display/tournament_view.html.twig', [
                'tournament' => $tournament,
                'matchesByPhase' => $matchesByPhase,
                'matchsRepository' => $matchsRepository
            ]);
            
        } else {
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

    
    private function getMatchesByPhase(TOURNAMENT $tournament, MATCHSRepository $matchsRepository): array
    {
        $matchesByPhase = [];
        $totalRounds = log($tournament->getNbMaxTeam(), 2); 

        for ($round = 1; $round <= $totalRounds; $round++) {
            $matchesInCurrentRound = $matchsRepository->findBy([
                'id_tournament' => $tournament,
                'phase' => $round
            ]);
            if (!empty($matchesInCurrentRound)) {
                $matchesByPhase[$round] = [];

                foreach ($matchesInCurrentRound as $match) {
                    $matchesByPhase[$round][] = $match->getId();
                }
            }
        }

        return $matchesByPhase;
    }

    public function generateRoundMatches(TOURNAMENT $tournament, REGISTERRepository $registerRepository, EntityManagerInterface $entityManager): void
    {
        $registers = $registerRepository->findBy(['id_tournament' => $tournament]);
        $teams = [];
    
        $undefinedTeam = $entityManager->getRepository(Team::class)->findOneBy(['name' => 'undefined']);
    
        if (!$undefinedTeam) {
            throw $this->createNotFoundException('L\'équipe avec le nom "undefined" n\'a pas été trouvée.');
        }
        foreach ($registers as $register) {
            $teams[] = $register->getIdTeam();
        }
        if (count($teams) % 2 !== 0) {
            throw $this->createNotFoundException('Le nombre d\'équipes doit être pair pour générer les matchs.');
        }
    
        $roundMatches = [];
        $phase = 1; 
        $totalTeams = count($teams); 
    
        while ($totalTeams > 1) {
            $numMatches = $totalTeams / 2;
    
            for ($i = 0; $i < $numMatches; $i++) {
                $match = new MATCHS();
                $match->setIdTournament($tournament);
                
                $match->setIdTeam1($undefinedTeam); 
                $match->setIdTeam2($undefinedTeam); 
                $match->setDate(new \DateTime()); 
                $match->setScoreTeam1(0);
                $match->setScoreTeam2(0);
    
                $match->setPhase($phase);
                $entityManager->persist($match);
                $roundMatches[$phase][] = $match;
            }
    
            $totalTeams = $numMatches; 
            $phase++; 
        }
    
        $entityManager->flush();
    }

}
