<?php

namespace App\Controller\Admin;

use App\Entity\REGISTER;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class REGISTERCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return REGISTER::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('id_user')->setCrudController(USERCrudController::class),
            AssociationField::new('id_team')->setCrudController(TEAMCrudController::class),
            AssociationField::new('id_tournament')->setCrudController(TOURNAMENTCrudController::class),
        ];
    }
}
