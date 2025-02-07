<?php

namespace App\Controller\Admin;

use App\Entity\MATCHS;
use App\Entity\TEAM;
use App\Entity\TOURNAMENT;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MATCHSCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MATCHS::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(), // Afficher l'ID uniquement en mode Index
            AssociationField::new('id_tournament')->setLabel('Tournament')->formatValue(fn ($value) => $value ? $value->getName() : 'No Tournament'), // Afficher le nom du tournoi
            AssociationField::new('id_team_1')->setLabel('Team 1')->formatValue(fn ($value) => $value ? $value->getName() : 'No Team'), // Afficher le nom de l’équipe 1
            AssociationField::new('id_team_2')->setLabel('Team 2')->formatValue(fn ($value) => $value ? $value->getName() : 'No Team'), // Afficher le nom de l’équipe 2
            DateTimeField::new('date')->setLabel('Date of Match'),
            IntegerField::new('score_team_1')->setLabel('Score Team 1'),
            IntegerField::new('score_team_2')->setLabel('Score Team 2'),
            IntegerField::new('phase')->setLabel('Phase')
        ];
    }
}
