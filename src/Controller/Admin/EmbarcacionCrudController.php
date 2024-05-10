<?php

namespace App\Controller\Admin;

use App\Entity\Embarcacion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EmbarcacionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Embarcacion::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideWhenCreating(),
            TextField::new('matricula'),
            TextField::new('nombre'),
            CountryField::new('bandera'),
            Field::new('alto'),
            Field::new('ancho'),
            Field::new('largo'),
            TextField::new('tipo'),
            AssociationField::new('usuario')->autocomplete(),
        ];
    }
    
}
