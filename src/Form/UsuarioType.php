<?php

namespace App\Form;

use App\Entity\Amarra;
use App\Entity\Usuario;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Use this instead
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',)
        //->add('roles')
            ->add('dni', TypeIntegerType::class, [ 
            'required'=>true,
            ])
            ->add('cuil', TypeIntegerType::class, [ 
            'required'=>true,
            ])
            ->add('nombre', TextType::class, [ 
            'required'=>true,
            ])
            ->add('nombre')
            ->add('apellido')
            ->add('telefono')
            ->add('direccion')
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
