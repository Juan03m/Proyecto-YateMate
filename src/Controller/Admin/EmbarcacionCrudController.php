<?php

namespace App\Controller\Admin;

use App\Entity\Embarcacion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\Amarra;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud; 
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class EmbarcacionCrudController extends AbstractCrudController
{



    public static function getEntityFqcn(): string
    {
        return Embarcacion::class;
    }
    // ...

 
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideWhenCreating()->hideWhenUpdating()->hideOnIndex(),
            TextField::new('matricula')->hideWhenUpdating(),
            TextField::new('nombre'),
            CountryField::new('bandera'),
            Field::new('manga')->setHelp('Mts'),
            Field::new('eslora')->setHelp('Mts'),
            Field::new('puntal')->setHelp('Mts'),
            TextField::new('tipo')->onlyOnIndex(),
            ChoiceField::new('tipo')->hideOnIndex()
            ->setFormTypeOptions([ // Permite seleccionar varios roles
                'choices' => [
                    'Yate' => 'Yate',
                    'Lancha' => 'Lancha',
                    'Bote' => 'Bote',
                    'Velero' => 'Velero',
                    'Pesquero' => 'Pesquero',
                    'Catamaran' => 'Catamaran',
                    'Barcaza' => 'Barcaza',
                ],
        ]),
            AssociationField::new('usuario')->autocomplete() ->hideWhenUpdating(),
            AssociationField::new('amarra')->autocomplete()-> hideWhenUpdating()
            ->setQueryBuilder(function (QueryBuilder $queryBuilder) {
                $queryBuilder
                ->andWhere('entity.embarcacion IS NULL');
            }),
        ];
    }


    public function configureFilters(Filters $filters): Filters
    {
        return $filters
        ->add('usuario');
    }




    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Embarcacion) {
            $amarra = $entityInstance->getAmarra();
            if ($amarra) {
                $amarra->setEmbarcacion(null);
                $entityInstance->setAmarra(null);
                $entityManager->persist($amarra);
                $entityManager->flush();
            }
        }



        parent::deleteEntity($entityManager, $entityInstance);
    }
    /*
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Embarcacion) {
            $amarra = $entityInstance->getAmarra();
            if ($amarra && $amarra->getEmbarcacion() === $entityInstance) {
                $amarra->setEmbarcacion(null);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
    */
    /*
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    if ($entityInstance instanceof Embarcacion) {
        $amarra = $entityInstance->getAmarra();
        $nuevaAmarra = $entityInstance->getAmarra();
        
        // Verifica si existe una amarra asociada a la embarcación
        if ($amarra) {

            // Asocia la amarra a la embarcación
            $amarra->setEmbarcacion($entityInstance);
            $entityManager->persist($amarra); // Guarda los cambios en la amarra
        }
        else {
            // Si la amarra es null, desasocia cualquier amarra actualmente asociada a la embarcación
                $nuevaAmarra->setEmbarcacion(null);
                $entityManager->persist($nuevaAmarra); // Guarda los cambios en la amarra actual
            }
        }
        // Guarda los cambios en la entidad Embarcacion
        $entityManager->persist($entityInstance);
        $entityManager->flush(); // Aplica los cambios en la base de datos

    parent::updateEntity($entityManager, $entityInstance);
    } 
    */
}

