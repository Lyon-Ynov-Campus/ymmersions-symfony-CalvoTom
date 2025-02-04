<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TOURNAMENTRepository;

final class DisplayController extends AbstractController
{
    #[Route('/display', name: 'app_display')]
    public function index(TOURNAMENTRepository $tournamentRepository): Response
    {
        $tournaments=$tournamentRepository->findAll();
        return $this->render('display/index.html.twig', [
            'controller_name' => 'DisplayController',
            'tournaments' => $tournaments
        ]);
    }
}
