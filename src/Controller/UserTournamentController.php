<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\REGISTERRepository;
use App\Repository\TOURNAMENTRepository;

final class UserTournamentController extends AbstractController
{
    #[Route('/mytournament', name: 'app_user_tournaments')]
    public function index(REGISTERRepository $registerRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos tournois.');
        }

        $registrations = $registerRepository->findBy(['id_user' => $user]);

        return $this->render('user_tournament/index.html.twig', [
            'registrations' => $registrations,
        ]);
    }

    #[Route('/mytournament/{id}', name: 'app_user_tournaments_teams')]
    public function userTeam(int $id, TOURNAMENTRepository $tournamentRepository, REGISTERRepository $registerRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir votre équipe.');
        }

        $tournament = $tournamentRepository->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException('Tournoi non trouvé');
        }

        $userRegistration = $registerRepository->findOneBy([
            'id_tournament' => $tournament,
            'id_user' => $user
        ]);

        if (!$userRegistration) {
            throw $this->createNotFoundException('Vous ne participez pas à ce tournoi.');
        }

        $team = $userRegistration->getIdTeam();
        $teamRegistrations = $registerRepository->findBy(['id_team' => $team]);

        $players = [];
        foreach ($teamRegistrations as $registration) {
            $players[] = $registration->getIdUser();
        }

        return $this->render('user_tournament/detail.html.twig', [
            'tournament' => $tournament,
            'team' => $team,
            'players' => $players,
        ]);
    }
}
