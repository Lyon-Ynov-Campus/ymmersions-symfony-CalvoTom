<?php

namespace App\Controller;

use App\Entity\TOURNAMENT;
use App\Entity\TEAM;
use App\Entity\REGISTER;
use App\Form\TournamentType;
use App\Form\TournamentRegistrationType;
use App\Repository\TOURNAMENTRepository;
use App\Repository\MATCHSRepository;
use App\Repository\TEAMRepository;
use App\Repository\REGISTERRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentController extends AbstractController
{
    #[Route('/tournament/create', name: 'app_tournament_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournament = new TOURNAMENT();
        $form = $this->createForm(TournamentType::class, $tournament);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tournament);
            $entityManager->flush();

            $this->addFlash('success', 'Tournament created successfully.');

            return $this->redirectToRoute('app_display');
        }

        return $this->render('tournament/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tournament/{id}/edit', name: 'app_tournament_edit')]
    public function edit(
        int $id,
        Request $request,
        TOURNAMENTRepository $tournamentRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $tournament = $tournamentRepository->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException('Tournoi non trouvé');
        }

        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Tournoi mis à jour avec succès.');
            return $this->redirectToRoute('app_display', ['id' => $tournament->getId()]);
        }

        $currentDate = new \DateTime();
        $isInProgress = $currentDate >= $tournament->getDateStart();

        return $this->render('tournament/edit.html.twig', [
            'form' => $form->createView(),
            'tournament' => $tournament,
            'isInProgress' => $isInProgress,
        ]);
    }

    #[Route('/tournament/{id}/register', name: 'app_tournament_register')]
    public function register(
        int $id,
        Request $request,
        TOURNAMENTRepository $tournamentRepository,
        TEAMRepository $teamRepository,
        REGISTERRepository $registerRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $tournament = $tournamentRepository->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException('Tournoi non trouvé');
        }

        $currentDate = new \DateTime();
        if ($currentDate > $tournament->getDateEndRegister()) {
            $this->addFlash('error', 'La période d\'inscription est terminée.');
            return $this->redirectToRoute('app_profile');
        }

        // Récupération des équipes déjà inscrites au tournoi
        $registers = $registerRepository->findBy(['id_tournament' => $tournament]);
        $teams = [];
        foreach ($registers as $register) {
            $teams[] = $register->getIdTeam();
        }

        $newTeam = new Team();
        $form = $this->createForm(TournamentRegistrationType::class, null, [
            'teams' => $teams,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team = $form->get('team')->getData();
            if ($team) {
                $newTeam = $team;
            } else {
                $newTeam->setName($form->get('new_team_name')->getData());
            }

            // Vérifier si l'équipe a déjà atteint le nombre maximum de joueurs
            $teamRegistrations = $registerRepository->findBy(['id_team' => $newTeam]);
            $maxPlayersPerTeam = $tournament->getNbMaxByTeam();

            if (count($teamRegistrations) >= $maxPlayersPerTeam) {
                $this->addFlash('error', 'Cette équipe a déjà atteint le nombre maximum de joueurs.');
                return $this->redirectToRoute('app_tournament_register', ['id' => $tournament->getId()]);
            }

            $entityManager->persist($newTeam);

            $register = new REGISTER();
            $register->setIdUser($this->getUser());
            $register->setIdTeam($newTeam);
            $register->setIdTournament($tournament);
            $entityManager->persist($register);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie.');
            return $this->redirectToRoute('app_display');
        }

        return $this->render('tournament/register.html.twig', [
            'form' => $form->createView(),
            'tournament' => $tournament,
        ]);
    }

}
