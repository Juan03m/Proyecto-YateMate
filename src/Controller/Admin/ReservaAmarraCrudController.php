<?php

namespace App\Controller\Admin;

use App\Entity\ReservaAmarra;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection as CollectionFilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityNotFoundException;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
class ReservaAmarraCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return ReservaAmarra::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // Personalizar la acción de ver
        $verAction = Action::new('detail', 'Ver detalle')
            ->linkToCrudAction(Crud::PAGE_DETAIL);
        $editarAction = Action::new('edit', 'Modificar descripción')
            ->linkToCrudAction(Crud::PAGE_EDIT);
        $aceptar = Action::new('aceptarReserva', 'Aceptar')
            ->linkToCrudAction('aceptarReserva')
            ->setCssClass('btn btn-success')
            ->displayIf(static function ($entity) {
                return $entity !== null && $entity->isAceptada() === null;
            });

        $cancelar = Action::new('cancelarReserva', 'Rechazar')
            ->linkToCrudAction('cancelarReserva')
            ->setCssClass('btn btn-danger')
            ->displayIf(static function ($entity) {
                return $entity !== null && $entity->isAceptada() === null;
            });

        return $actions
            ->disable(Action::NEW, Action::DELETE)
            ->add(Crud::PAGE_INDEX, $verAction)
            ->add(Crud::PAGE_DETAIL, $aceptar)
            ->add(Crud::PAGE_DETAIL, $cancelar);
    }

    public function aceptarReserva(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): Response
    {
        $entity = $context->getEntity()->getInstance();
        if ($entity && $entity->isAceptada() === null) {
            $entity->setAceptada(true);
            $entityManager->persist($entity);
            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('La reserva ha sido aceptada.'));
        }

        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function cancelarReserva(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): Response
    {
        $entity = $context->getEntity()->getInstance();
        if ($entity && $entity->isAceptada() === null) {
            $entity->setAceptada(false);
            $entityManager->persist($entity);
            $entityManager->flush();
            $this->addFlash('danger', $this->translator->trans('La reserva ha sido rechazada.'));
        }

        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Descripción de la reserva') // Cambia el nombre singular de la entidad
            ->setEntityLabelInPlural('Reservas de amarras'); // Cambia el nombre plural de la entidad
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('publicacionAmarra')->setDisabled(true)->hideWhenUpdating(),
            AssociationField::new('solicitante')->setDisabled(true)->hideWhenUpdating(),
            DateField::new('fechaDesde')->setDisabled(true)->hideWhenUpdating(),
            DateField::new('fechaHasta')->setDisabled(true)->hideWhenUpdating(),
            TextareaField::new('descripcion')->formatValue(static function ($value, $entity) {
                $maxLength = 50;
                return $value ? (strlen($value) > $maxLength ? substr($value, 0, $maxLength) . '...' : $value) : 'No tiene';
            }),
            BooleanField::new('aceptada')->hideWhenUpdating()
            ->setLabel('Asistió')->renderAsSwitch(false),
        ];
    }
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, CollectionFilterCollection $filters): ORMQueryBuilder
    {
        $qb = $this->entityManager->createQueryBuilder();

        // Obtener la fecha de hoy como un objeto DateTime
        $today = new \DateTime();

        $qb->select('entity')
            ->from($entityDto->getFqcn(), 'entity')
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->lte('entity.fechaDesde', ':today'),
                    $qb->expr()->gt('entity.fechaHasta', ':today'),
                )

            )
            ->setParameter('today', $today->format('Y-m-d'));

        return $qb;
    }
}
