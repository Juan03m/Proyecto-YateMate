<?php

namespace App\Controller\Admin;

use App\Entity\Solicitud;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;

class SolicitudCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return Solicitud::class;
    }

    /*public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('aprobado'));
    }
*/
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, CollectionFilterCollection $filters): ORMQueryBuilder
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('entity')
            ->from($entityDto->getFqcn(), 'entity');

        $qb->andWhere('entity.aceptada = :status')
            ->setParameter('status', true);

        $filtro = $searchDto->getAppliedFilters('aprobado');

        if (!$filtro) {
            $qb->andWhere('entity.aprobado IS NULL');
        } else {
            if ($filtro['aprobado'] == 1) {
                $qb->andWhere('entity.aprobado = true');
            } else {
                $qb->andWhere('entity.aprobado = false');
            }
        }

        return $qb;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('solicitado')->hideOnForm(),
            AssociationField::new('embarcacion')->hideOnForm(),
            AssociationField::new('bien')->hideOnForm(),
            AssociationField::new('solicitante')->hideOnForm(),
            BooleanField::new('aprobado')->hideOnIndex()
        ];
    }

    public function aceptarIntercambio(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator, MailerInterface $mailer): Response
    {
        try{
        $entity = $context->getEntity()->getInstance();
        if ($entity && $entity->isAprobado() === null) {
            $embarcacion = $entity->getEmbarcacion();
            $solicitado = $entity->getSolicitado();
            $solicitante = $entity->getSolicitante();
            $bien= $entity->getBien();

            if (!in_array('ROLE_CLIENT', $solicitante->getRoles(), true)) {
                $roles = $solicitante->getRoles();
                $roles[] = 'ROLE_CLIENT';
                $solicitante->setRoles($roles);
            }


            $embarcacion->setUsuario($solicitante);
            $entity->setAprobado(true);

            //$embarcacion->borrarSolicitudes();
       

            $bien = $entity->getBien();
            $bien->setOwner($solicitado);
            
            $date = new \DateTime();
            $date->modify('+2 days');

            $dateString = $date->format('d/m/Y');
            $mensaje = 'Felicidades, tu intercambio de la embarcación ' . $embarcacion->getNombre() . ' ha sido aprobado';
            $email = (new Email())
                ->from('GSQInteractive1@yopmail.com')
                ->to($solicitado->getEmail())
                ->subject('Información de Intercambios!')
                ->text($mensaje);
            $mailer->send($email);

            $email = (new Email())
                ->from('GSQInteractive1@yopmail.com')
                ->to($solicitante->getEmail())
                ->subject('Información de Intercambios!')
                ->text($mensaje);
            $mailer->send($email);



            $publicacion=$entity->getPublicacion();
            $entityManager->remove($publicacion);

            $solicitudesRelacionadasEmbarcacion= $embarcacion->getSolicitudes();
            $solicitudesRelacionadasBien= $bien->getSolicitudes();

            foreach ($solicitudesRelacionadasEmbarcacion as $solicitudRelacionada) {
                $entityManager->remove($solicitudRelacionada);
            }

            foreach ($solicitudesRelacionadasBien as $solicitudBien) {
                $entityManager->remove($solicitudBien);
            }








            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('El intercambio ha sido aceptado con éxito.'));
        }

        $url = $adminUrlGenerator->setController(DashboardController::class)
            ->setAction(Crud::PAGE_INDEX) // Redirigir al index
            ->generateUrl();

        return $this->redirect($url);




        }catch (EntityNotFoundException $exception) {
            // Manejo de la excepción
            $this->addFlash('danger', 'La solicitud no existe.');
            
            // Redirigir a otra página
            return $this->redirectToRoute('/admin');
        }
    }

    public function cancelarIntercambio(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator, MailerInterface $mailer): Response
    {
        $entity = $context->getEntity()->getInstance();
        if ($entity && $entity->isAprobado() === null) {
            $entity->setAprobado(false);
            $entity->setAceptada(false);
            $embarcacion = $entity->getEmbarcacion();
            $solicitado = $entity->getSolicitado();
            $solicitante = $entity->getSolicitante();

            $mensaje = 'Lamentamos informarte que el intercambio pendiente de la embarcación ' . $embarcacion->getNombre() . ' ha sido rechazado';
            $email = (new Email())
                ->from('GSQInteractive1@yopmail.com')
                ->to($solicitado->getEmail())
                ->subject('Información de Intercambios!')
                ->text($mensaje);
            $mailer->send($email);

            $email = (new Email())
                ->from('GSQInteractive1@yopmail.com')
                ->to($solicitante->getEmail())
                ->subject('Información de Intercambios!')
                ->text($mensaje);
            $mailer->send($email);


            $entityManager->remove($entity);
            $entityManager->flush();

            $this->addFlash('danger', $this->translator->trans('El intercambio ha sido rechazado.'));
        }

        $url = $adminUrlGenerator->setController(DashboardController::class)
            ->setAction(Crud::PAGE_INDEX) // Redirigir al dashboard
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureActions(Actions $actions): Actions
    {
        try {
            $aceptar = Action::new('Aceptar Intercambio', 'Aceptar intercambio')
                ->linkToCrudAction('aceptarIntercambio')
                ->setCssClass('btn btn-success')
                ->displayIf(static function ($entity) {
                    // Verifica si $entity no es nulo antes de llamar a isAprobado()
                    return $entity !== null && $entity->isAprobado() === null;
                });
    
            $cancelar = Action::new('Rechazar Intercambio', 'Rechazar intercambio')
                ->linkToCrudAction('cancelarIntercambio')
                ->setCssClass('btn btn-danger')
                ->displayIf(static function ($entity) {
                    // Verifica si $entity no es nulo antes de llamar a isAprobado()
                    return $entity !== null && $entity->isAprobado() === null;
                });
    
            return $actions
                ->add(Crud::PAGE_INDEX, Action::DETAIL)
                ->disable(Action::NEW, Action::DELETE, Action::EDIT)
                ->add(Crud::PAGE_DETAIL, $aceptar)
                ->add(Crud::PAGE_DETAIL, $cancelar);
        } catch (EntityNotFoundException $exception) {
            // Manejo de la excepción
            $this->addFlash('danger', 'La solicitud no existe.');
            
            // Redirigir a otra página
            return $this->redirect($this->generateUrl('/admin'));
        }
    }
}
