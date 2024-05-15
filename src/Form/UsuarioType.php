<?php

namespace App\Form;

use App\Entity\Amarra;
use App\Entity\Usuario;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
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
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'exactMessage' => 'Tu número de identificación debe tener al menos {{ limit }} caracteres',
                        'max' => 8,
                    ]),
                ],
            ])
            ->add('cuil')
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
