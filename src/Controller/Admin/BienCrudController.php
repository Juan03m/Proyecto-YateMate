<?php

namespace App\Controller\Admin;

use App\Entity\Bien;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BienCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bien::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $marinas=['Marina Punta Lara','Marina Tigre','Marina Concepcion del Uruguay'];
        return [
            TextField::new('nombre'),
            TextField::new('descripcion'),
            ChoiceField::new('tipo')->hideOnIndex()
            ->setFormTypeOptions([ // Permite seleccionar varios roles
                'choices' => array_flip($marinas)
                
        ])
        ];
    }
    
}
