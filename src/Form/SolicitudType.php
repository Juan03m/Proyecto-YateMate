<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Solicitud;
use App\Repository\BienRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usuario = $options['user'];
        $selectedBien = $options['selectedBien'];

        $builder
            ->add('descripcion', null, [
            'required' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'La descripcion no puede estar vacia',
                ]),
            ],
         ])
            ->add('bien', EntityType::class, [
                'class' => Bien::class,
                'choice_label' => 'nombre',
                'query_builder' => function (BienRepository $er) use ($usuario) {
                    return $er->createQueryBuilder('e')
                        ->where('e.owner = :usuario')
                        ->setParameter('usuario', $usuario);
                },
                'data' => $selectedBien, // Preselect the bien
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
            'user' => null,
            'selectedBien' => null,
        ]);

        $resolver->setDefined('selectedBien');
    }
}

