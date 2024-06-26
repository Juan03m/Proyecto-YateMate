<?php

namespace App\Form;

use App\Entity\PublicacionAmarra;
use App\Entity\ReservaAmarra;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class ReservaAmarraType extends AbstractType
{
     private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $idPublicacion = $options['idPublicacion'] ?? null;

        $publicacionAmarra=$options['publicacion'];


       // dd($this->entityManager->getRepository(PublicacionAmarra::class));

        $rp = $this->entityManager->getRepository(PublicacionAmarra::class);

       // $fechasOcupadas=$rp->getFechasOcupadas($idPublicacion);
        





        $publicacionOptions = [
            'class' => PublicacionAmarra::class,
            'constraints' => [
                new NotBlank([
                    'message' => 'Selecciona una publicación de amarra',
                ]),
            ],
        ];

        if ($idPublicacion) {
            $publicacionOptions['disabled'] = true;
        }

        $builder

    

            ->add('publicacionAmarra', EntityType::class, $publicacionOptions)
            ->add('fechaDesde', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
               
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa una fecha de inicio',
                    ]),
                ],
            ])
            ->add('fechaHasta', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
               // 'disable_dates'=>$fechasOcupadas,
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa una fecha de finalización',
                    ]),
                ],
            ]);

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $reservaAmarra = $event->getData();
                $publicacionAmarra = $reservaAmarra->getPublicacionAmarra();

                if ($publicacionAmarra) {
                    $fechaDesde = $reservaAmarra->getFechaDesde();
                    $fechaHasta = $reservaAmarra->getFechaHasta();

                    if ($fechaDesde && $fechaHasta) {
                        if ($fechaHasta <= $fechaDesde) {
                            $form->get('fechaHasta')->addError(new FormError('La fecha de finalización debe ser mayor que la fecha de inicio.'));
                        }
                        
                        if ($fechaDesde < $publicacionAmarra->getFechaDesde() ) {
                            $form->get('fechaDesde')->addError(new FormError('La fecha de INICIO de la reserva debe estar dentro del rango de fechas de la publicación de amarra.'));
                        }
                        if ($fechaHasta > $publicacionAmarra->getFechaHasta()) {
                            $form->get('fechaHasta')->addError(new FormError('La fecha de FIN de la reserva debe estar dentro del rango de fechas de la publicación de amarra.'));
                        }
                        $hasOverlap = false;

                        foreach ($publicacionAmarra->getReservaAmarra() as $existingReserva) {
                            $isOverlapping = 
                                ($fechaDesde >= $existingReserva->getFechaDesde() && $fechaDesde <= $existingReserva->getFechaHasta()) ||
                                ($fechaHasta >= $existingReserva->getFechaDesde() && $fechaHasta <= $existingReserva->getFechaHasta()) ||
                                ($fechaDesde <= $existingReserva->getFechaDesde() && $fechaHasta >= $existingReserva->getFechaHasta());
                        
                            if ($isOverlapping && $existingReserva->isAceptada() !== false) {
                                $hasOverlap = true;
                                break;
                            }
                        }
                        
                        if ($hasOverlap) {
                            $form->get('fechaDesde')->addError(new FormError('Las fechas de la reserva se solapan con una reserva existente aceptada o pendiente.'));
                        }
                    }
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservaAmarra::class,
            'idPublicacion' => null,
            'publicacion'=>null,
        ]);
    }
}
