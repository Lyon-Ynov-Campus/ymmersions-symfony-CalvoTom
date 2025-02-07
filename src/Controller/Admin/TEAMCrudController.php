<?php

namespace App\Controller\Admin;

use App\Entity\TEAM;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TEAMCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TEAM::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
        ];
    }
}
