<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\Query\Expr\Join;

class UsuarioCrudController extends AbstractCrudController
{
    private $requestStack;
    private $translator;

    public function __construct(RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return Usuario::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // Remove the 'new' action from the index view
        return $actions
            ->disable(Action::NEW)
            ->disable(Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideWhenCreating()->hideWhenUpdating()->hideOnIndex(),
            TextField::new('email')->hideWhenUpdating(),
            // BooleanField::new('isVerified'),
            TextField::new('password')->onlyWhenCreating()->setFormType(PasswordType::class),
            TextField::new('nombre')->hideWhenUpdating(),
            TextField::new('apellido')->hideWhenUpdating(),
            TextField::new('dni')->hideWhenUpdating(),
            TextField::new('cuil')->hideWhenUpdating(),
            TextField::new('telefono')->hideWhenUpdating(),
            TextField::new('direccion')->hideWhenUpdating(),
            ChoiceField::new('roles')
                ->setFormTypeOptions([
                    'multiple' => true, // Permite seleccionar varios roles
                    'choices' => [
                        'Cliente' => 'ROLE_CLIENT',
                        'Usuario' => 'ROLE_USER',
                        'Administrador' => 'ROLE_ADMIN',
                    ],
                ])
                ->hideOnIndex(),
            AssociationField::new('embarcaciones')
                    ->setFormTypeOptions([
                        'by_reference' => false,
                        'multiple' => true, 
                    ])
                    ->setQueryBuilder(function (QueryBuilder $queryBuilder) {
                    // Obtén la entidad de usuario actual
                    $usuario = $this->getContext()->getEntity()->getInstance();

                    // Verifica si el usuario tiene solo el rol 'ROLE_USER'
                    if (count($usuario->getRoles()) === 1 && in_array('ROLE_USER', $usuario->getRoles(), true)) {
                        // Si el usuario tiene solo el rol 'ROLE_USER', no se muestran 'embarcaciones'
                         $queryBuilder->where('1 = 0');
                    }   else {
                        // Obtén el ID del usuario actual en edición
                        $usuarioId = $usuario->getId();

                        $queryBuilder
                        ->where('entity.usuario IS NULL OR entity.usuario = :usuario')
                        ->setParameter('usuario', $usuarioId);
                        }
                    }),
                AssociationField::new('amarras')
                    ->setFormTypeOptions([
                        'by_reference' => false,
                        'multiple' => true, 
                    ])
                    ->setQueryBuilder(function (QueryBuilder $queryBuilder) {
                    // Obtén la entidad de usuario actual
                    $usuario = $this->getContext()->getEntity()->getInstance();

                    // Verifica si el usuario tiene solo el rol 'ROLE_USER'
                    if (count($usuario->getRoles()) === 1 && in_array('ROLE_USER', $usuario->getRoles(), true)) {
                        // Si el usuario tiene solo el rol 'ROLE_USER', no se muestran 'amarras'
                         $queryBuilder->where('1 = 0');
                    }   else {
                        // Obtén el ID del usuario actual en edición
                        $usuarioId = $usuario->getId();

                        $queryBuilder
                        ->where('entity.usuario IS NULL OR entity.usuario = :usuario')
                        ->setParameter('usuario', $usuarioId);
                        }
                    }),
        ];
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Usuario) {
            // Desasociar embarcaciones del usuario antes de eliminar al usuario
            foreach ($entityInstance->getEmbarcaciones() as $embarcacion) {
                $embarcacion->setUsuario(null);
                $entityManager->persist($embarcacion);
            }
            $entityManager->flush();

            // Agregar un mensaje flash después de eliminar el usuario
            $this->addFlash('danger', $this->translator->trans('Usuario eliminado exitosamente'));
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }
}
