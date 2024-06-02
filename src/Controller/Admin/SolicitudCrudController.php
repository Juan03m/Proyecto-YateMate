<?php

namespace App\Controller\Admin;

use App\Entity\Solicitud;
use Doctrine\DBAL\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\FilterCollection;
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
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SolicitudCrudController extends AbstractCrudController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }








    public static function getEntityFqcn(): string
    {
        return Solicitud::class;
    }



    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('aprobado'));
            

    }







 
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, CollectionFilterCollection $filters): ORMQueryBuilder
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('entity')
           ->from($entityDto->getFqcn(), 'entity');

        // Agrega tus filtros predeterminados aquí
        $qb->andWhere('entity.aceptada = :status')
           ->setParameter('status', true);
        
        $filtro=$searchDto->getAppliedFilters('aprobado');

        if (!$filtro) {
            $qb->andWhere('entity.aprobado IS NULL');
        }
        else
            if($filtro['aprobado']==1){
            $qb->andWhere('entity.aprobado = true ');
            }
            else{
                $qb->andWhere('entity.aprobado = false');
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



    public function aceptarIntercambio(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator,MailerInterface $mailer): Response
    {
        // Obtener la entidad actual del contexto
        $entity = $context->getEntity()->getInstance();
        // Asegurarse de que la entidad no sea nula
        if ($entity) {
            // Realizar la lógica deseada
            $embarcacion=$entity->getEmbarcacion();
            $solicitado=$entity->getSolicitado();
            $solicitante=$entity->getSolicitante();

            $solicitante->setRoles(['ROlE_USER','ROLE_CLIENT']);

            $embarcacion->setUsuario($solicitante);
            $entity->setAprobado(true); // Ejemplo de actualización del campo

            $mensaje='Felicidades, tu intermcambio de la embarcacion'.' '.$embarcacion->getNombre().'ha sido aprobado';
            $email = (new Email())
            ->from('GSQInteractive@yopmail.com')
            ->to($solicitado->getEmail())
            ->subject('Informacion de Intercambios!')
            ->text($mensaje);
            $mailer->send($email);


            $mensaje='Felicidades, tu intermcambio de la embarcacion'.' '.$embarcacion->getNombre().'ha sido aprobado';
            $email = (new Email())
            ->from('GSQInteractive@yopmail.com')
            ->to($solicitante->getEmail())
            ->subject('Informacion de Intercambios!')
            ->text($mensaje);
            $mailer->send($email);




            // Persistir los cambios
            $entityManager->flush();
        }

        // Redirigir de vuelta a la página de detalles de la entidad
        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Crud::PAGE_DETAIL)
            ->setEntityId($entity->getId())
            ->generateUrl();

        return $this->redirect($url);
    }


    public function cancelarIntercambio(AdminContext $context, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator,MailerInterface $mailer):Response{
         // Obtener la entidad actual del contexto
         $entity = $context->getEntity()->getInstance();
         // Asegurarse de que la entidad no sea nula
         if ($entity) {


             // Realizar la lógica deseada
             $entity->setAprobado(false); // Ejemplo de actualización del campo
             $entity->setAceptada(false);
            $embarcacion=$entity->getEmbarcacion();
            $solicitado=$entity->getSolicitado();
            $solicitante=$entity->getSolicitante();

             $mensaje='Lamentamos informarte que el intercambio pendiente de la embarcacion'.' '.$embarcacion->getNombre().'ha sido rechazado';
             $email = (new Email())
             ->from('GSQInteractive@yopmail.com')
             ->to($solicitado->getEmail())
             ->subject('Informacion de Intercambios!')
             ->text($mensaje);
             $mailer->send($email);
 

             $mensaje='Lamentamos informarte que el intercambio pendiente de la embarcacion'.' '.$embarcacion->getNombre().'ha sido rechazado';
             $email = (new Email())
             ->from('GSQInteractive@yopmail.com')
             ->to($solicitante->getEmail())
             ->subject('Informacion de Intercambios!')
             ->text($mensaje);
             $mailer->send($email);
 






 
             // Persistir los cambios
             $entityManager->flush();
         }
 
         // Redirigir de vuelta a la página de detalles de la entidad
         $url = $adminUrlGenerator->setController(self::class)
             ->setAction(Crud::PAGE_DETAIL)
             ->setEntityId($entity->getId())
             ->generateUrl();
 
         return $this->redirect($url);
    }












    public function configureActions(Actions $actions): Actions
    {

        $className = $this->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);


      
        $aceptar=Action::new('Aceptar Intercambio','Aceptar intercambio')
            ->linkToCrudAction('aceptarIntercambio')
            ->setCssClass('btn btn-success');


        $cancelar=Action::new('Rechazar Intercambio',null)
            ->linkToCrudAction('cancelarIntercambio')
            ->setCssClass('btn btn-danger');
        return $actions

            ->add(Crud::PAGE_INDEX,Action::DETAIL)
            ->disable(Action::NEW,Action::DELETE)
            ->add(Crud::PAGE_DETAIL,$aceptar)
            ->add(Crud::PAGE_DETAIL,$cancelar);
           // ->add(Crud::PAGE_INDEX,$aceptar);
            //->remove(Crud::PAGE_INDEX,Action::NEW);

    }



}
