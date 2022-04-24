<?php

namespace App\Form;

use App\Entity\PupupupupUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class PupupupupUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 10,
                        'minMessage' => "Your first name must be at least 5 characters long",
                        'maxMessage' => "Your first name cannot be longer than 10 characters",
                    ]),
                    new Regex(
                        "[a-z]+",
                        "Invalid character",
                    )
                ],
            ])
            ->add('age', NumberType::class)
            ->add('salary', NumberType::class)
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PupupupupUser::class,
        ]);
    }
}
