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
                'input_format' => 'dd-mm-yyyy',
                'label' => 'Fecha de nacimiento',
                'widget' => 'single_text',
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => '-18 years',
                        'message' => 'Debes ser mayor de 18 años para registrarte.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[^a-zA-Z\d]).+$/',
                        'message' => 'La contraseña debe contener al menos una mayúscula y un carácter especial',
                    ]),
                ],
            ]);
          /*  ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'No olvides aceptar nuestros terminos y condiciones.',
                    ]),
                ],
            ]); */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}