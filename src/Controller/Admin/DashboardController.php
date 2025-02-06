<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\USERCrudController;
use App\Entity\USER;
use App\Entity\MATCHS;
use App\Entity\TOURNAMENT;
use App\Entity\REGISTER;
use App\Entity\TEAM;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    #[Route(path: '/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')] 
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(USERCrudController::class)->generateUrl());
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MVP');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('USER', 'fas fa-list', USER::class);
        yield MenuItem::linkToCrud('TEAM', 'fas fa-list', TEAM::class);
        yield MenuItem::linkToCrud('MATCHS', 'fas fa-list', MATCHS::class);
        yield MenuItem::linkToCrud('TOURNAMENT', 'fas fa-list', TOURNAMENT::class);
        yield MenuItem::linkToCrud('REGISTER', 'fas fa-list', REGISTER::class);
    }
}
