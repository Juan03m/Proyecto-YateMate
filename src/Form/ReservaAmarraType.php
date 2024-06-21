<?php

namespace App\Form;

use App\Entity\PublicacionAmarra;
use App\Entity\ReservaAmarra;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class ReservaAmarraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publicacionAmarra', EntityType::class, [
                'class' => PublicacionAmarra::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Selecciona una publicación de amarra',
                    ]),
                ],
            ])
            ->add('fechaDesde', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'), // Establecer mínimo como fecha actual en formato Y-m-d
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
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'), // Establecer mínimo como fecha actual en formato Y-m-d
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa una fecha de finalización',
                    ]),
                ],
            ]);

        // Añadir la validación personalizada para las fechas
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $reservaAmarra = $event->getData();

                // Obtener la publicación de amarra seleccionada en el formulario
                $publicacionAmarra = $reservaAmarra->getPublicacionAmarra();

                if ($publicacionAmarra) {
                    $fechaDesde = $reservaAmarra->getFechaDesde();
                    $fechaHasta = $reservaAmarra->getFechaHasta();

                    if ($fechaDesde && $fechaHasta) {
                        if ($fechaHasta <= $fechaDesde) {
                            $form->get('fechaHasta')->addError(new FormError('La fecha de finalización debe ser mayor que la fecha de inicio.'));
                        }
                        if($fechaDesde < $publicacionAmarra->getFechaDesde() && $fechaHasta > $publicacionAmarra->getFechaHasta()){
                            $form->get('fechaDesde')->addError(new FormError('La fecha de INICIO de la reserva debe estar dentro del rango de fechas de la publicación de amarra.'));
                            $form->get('fechaHasta')->addError(new FormError('La fecha de FIN de la reserva debe estar dentro del rango de fechas de la publicación de amarra.'));
                        }
                        elseif ($fechaDesde < $publicacionAmarra->getFechaDesde()) {
                            $form->get('fechaDesde')->addError(new FormError('La fecha de INICIO de la reserva debe estar dentro del rango de fechas de la publicación de amarra.'));
                        }
                        elseif($fechaHasta > $publicacionAmarra->getFechaHasta()){
                            $form->get('fechaHasta')->addError(new FormError('La fecha de FIN de la reserva debe estar dentro del rango de fechas de la publicación de amarra.'));
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
        ]);
    }
}
