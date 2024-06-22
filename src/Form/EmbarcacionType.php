<?php

namespace App\Form;

use App\Entity\Embarcacion;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

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
                new UniqueEntity(fields: ['Matricula']),
            ],
        ]);
    }

    
}
