<?php

namespace App\Form;

use App\Entity\Usuario;
use Doctrine\DBAL\Types\DateType;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('email')
        ->add('nombre')
        ->add('apellido')
        ->add('fechaNacimiento', TypeDateType::class, [
            'label' => 'Fecha de nacimiento',
            'widget' => 'single_text',
            // Agregar la restricción de edad mínima
            'constraints' => [
                new LessThanOrEqual([
                    'value' => '-18 years', // La fecha mínima es hace 18 años
                    'message' => 'Debes ser mayor de 18 años para registrarte.',
                ]),
               
            ],
            // Otras opciones del campo...
        ])
        

        
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'No olvides aceptar nuestros terminos y condiciones.',
                ]),
            ],
        ])
        
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
                new Regex([
                    'pattern' => '/^(?=.*[A-Z])(?=.*[^a-zA-Z\d]).+$/',
                    'message' => 'La contraseña debe contener al menos una mayúscula y un carácter especial',
                ]),
            ],
        ]);
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
