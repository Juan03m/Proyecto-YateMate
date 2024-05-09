<?php

namespace App\Form;

use App\Entity\Amarra;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',)
            //->add('roles')
            ->add('password')
            ->add('dni')
            ->add('cuil')
            ->add('nombre')
            ->add('apellido')
            ->add('telefono')
            ->add('direccion')
            
            ->add('amarra', EntityType::class, [
                'class' => Amarra::class,
                'choice_label' => 'id',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
