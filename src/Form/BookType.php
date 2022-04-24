<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Shop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('pageQuantity', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Field can not be blank'
                    ]),
                ]
            ])
            ->add('shop', EntityType::class, [
                'class' => Shop::class,
                'choice_label' => 'title',
                'placeholder' => 'Select shop',
                'required' => false,
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Book::class,
        ]);
    }
}
