<?php
namespace App\Form;

use App\Entity\Amarra;
use App\Entity\PublicacionAmarra;
use App\Entity\Usuario;
use App\Repository\AmarraRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PublicacionAmarraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usuario = $options['user'];

        $builder
        ->add('fechaDesde', DateType::class, [
            'widget' => 'single_text',
            'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
            'attr' => [
                'min' => (new \DateTime())->modify('+1 day')->format('Y-m-d'),
                'max' =>(new \DateTime())->modify('+2 year')->format('Y-m-d'), // Establecer el mínimo como la fecha actual en formato Y-m-d
                'placeholder' => 'Fecha desde dd/mm/AAAA'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Por favor ingresa una fecha de inicio',
                ]),
            ],
        ])
        ->add('fechaHasta', DateType::class, [
            'widget' => 'single_text',
            'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
            'attr' => [
                'min' =>(new \DateTime())->modify('+4 day')->format('Y-m-d'),
                'max' =>(new \DateTime())->modify('+2 year')->format('Y-m-d'),
                'placeholder' => 'Fecha hasta dd/mm/AAAA' 
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Por favor ingresa una fecha de finalización',
                ]),
            ],
        ])
        
            ->add('amarra', EntityType::class, [
                'class' => Amarra::class,
                'query_builder' => function (AmarraRepository $ar) use ($usuario) {
                    return $ar->createQueryBuilder('a')
                        ->where('a.usuario = :usuario')
                        ->setParameter('usuario', $usuario);
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'No tienes amarras para publicar',
                    ]),
                ],
                
            ]);

        // Añadir la validación personalizada para las fechas
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $fechaDesde = $data->getFechaDesde();
                $fechaHasta = $data->getFechaHasta();

                if ($fechaDesde && $fechaHasta) {
                    $fechaMinimaHasta = (clone $fechaDesde)->modify('+2 days');
                    if ($fechaHasta <= $fechaMinimaHasta) {
                        $form->get('fechaHasta')->addError(new FormError('La fecha de finalización debe ser 3 días posterior que la fecha de inicio.'));
                    }

                    // Verificar que la diferencia entre fechaDesde y fechaHasta no sea mayor a 3 meses
                    $fechaMaximaHasta = (clone $fechaDesde)->modify('+3 months');
                    if ($fechaHasta > $fechaMaximaHasta) {
                        $form->get('fechaHasta')->addError(new FormError('La diferencia entre la fecha desde y la fecha hasta no puede ser mayor a 3 meses.'));
                    }
                }
            }
        );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PublicacionAmarra::class,
            'user' => null,
        ]);
    }
}
