<?php

namespace App\Controller\Admin;

use App\Entity\Amarra;
use App\Form\AmarraFormType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class AmarraCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Amarra::class;
    }

    public function configureFields(string $pageName): iterable
    {   
        return [
            IdField::new('id')->hideWhenCreating()->hideWhenUpdating(),
            IntegerField::new('numero'),
            IntegerField::new('sector'),
            ChoiceField::new('marina')->setChoices([
                'Norte' => 'Norte',
                'Centro' => 'Centro',
                'Sur' => 'Sur',
                'Este' => 'Este',
                'Oeste' => 'Oeste',
                'Delta' => 'Delta',
                'Bahia' => 'Bahia',
                'Atlantico' => 'Atlantico',
            ]),
            AssociationField::new('embarcacion')
                ->autocomplete()
                ->setQueryBuilder(function (QueryBuilder $queryBuilder) {
                    $queryBuilder
                        ->leftJoin('App\Entity\Amarra', 'a', Join::WITH, 'a.embarcacion = entity.id')
                        ->where('a.embarcacion IS NULL');
                }),
        ];
    }
}
