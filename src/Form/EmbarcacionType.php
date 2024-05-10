<?php

namespace App\Form;

use App\Entity\Embarcacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmbarcacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Matricula', TextType::class,['constraints' => [new Length(['min' => 10])]])
            ->add('Nombre')
            ->add('alto')
            ->add('ancho')
            ->add('largo')
            ->add('Bandera')
            ->add('Tipo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Embarcacion::class,
            'constraints' => [
                new UniqueEntity(fields: ['Nombre']),
            ],
        ]);
    }

    
}
