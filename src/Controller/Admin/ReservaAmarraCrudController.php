<?php

namespace App\Controller\Admin;

use App\Entity\ReservaAmarra;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class ReservaAmarraCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReservaAmarra::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        // Remove the 'new' and 'delete' actions from the CRUD for ReservaAmarra
        return $actions
            ->disable(Action::NEW);
    }
    
    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('publicacionAmarra')
            ->setDisabled(true),
            AssociationField::new('solicitante')
            ->setDisabled(true),
            DateField::new('fechaDesde')
            ->setDisabled(true),
            DateField::new('fechaHasta')
            ->setDisabled(true),
        ];
    }
    
}
