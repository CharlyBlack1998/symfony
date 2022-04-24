<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Product;
use App\Entity\Shop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('address', TextType::class)
            ->add('products', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'title',
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('books', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'title',
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Shop::class,
        ]);
    }
}
