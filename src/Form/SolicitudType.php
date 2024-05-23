<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Embarcacion;
use App\Entity\Solicitud;
use App\Entity\Usuario;
use App\Repository\BienRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usuario=$options['user'];

        $builder
            ->add('descripcion')
            /*
            ->add('solicitado', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ])
            ->add('solicitante', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ])

            */
            ->add('bien', EntityType::class, [
                'class' => Bien::class,
                'choice_label' => 'nombre',
                'query_builder' => function (BienRepository $er) use ($usuario) {
                    return $er->createQueryBuilder('e')
                        ->where('e.owner = :usuario')
                        ->setParameter('usuario', $usuario);
                },
                

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
            'user'=>null,
        ]);
    }
}
