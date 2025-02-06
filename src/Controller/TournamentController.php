<?php

namespace App\Controller;

use App\Entity\TOURNAMENT;
use App\Form\TournamentType;
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
        MATCHSRepository $matchsRepository,
        TEAMRepository $teamRepository,
        REGISTERRepository $registerRepository,
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
            return $this->redirectToRoute('app_tournament_edit', ['id' => $tournament->getId()]);
        }

        $matches = $matchsRepository->findBy(['id_tournament' => $tournament]);

        $registers = $registerRepository->findBy(['id_tournament' => $tournament]);
        $teams = [];
        foreach ($registers as $register) {
            $teams[] = $register->getIdTeam();
        }

        $currentDate = new \DateTime();
        $isInProgress = $currentDate >= $tournament->getDateStart();

        return $this->render('tournament/edit.html.twig', [
            'form' => $form->createView(),
            'tournament' => $tournament,
            'matches' => $matches,
            'teams' => $teams,
            'isInProgress' => $isInProgress,
        ]);
    }
}
