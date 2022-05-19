<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder
//            ->add('country', EntityType::class, [
//                'class' => Country::class,
//                'choice_label' => 'name',
//                'multiple' => false
//                ])
//            ->add('region')
//            ->add('city')
//            ->add('street')
//            ->add('houseNumber')
//            ->add('postalCode')
//        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
