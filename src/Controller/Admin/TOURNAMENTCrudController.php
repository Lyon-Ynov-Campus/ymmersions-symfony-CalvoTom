<?php

namespace App\Controller\Admin;

use App\Entity\TOURNAMENT;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class TOURNAMENTCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TOURNAMENT::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'), 
            DateField::new('date_start_register'), 
            DateField::new('date_end_register'), 
            DateField::new('date_start'), 
        ];
    }
}
