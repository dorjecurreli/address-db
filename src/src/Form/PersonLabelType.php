<?php

namespace App\Form;

use App\Entity\PersonLabel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonLabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isBlacklisted')
            ->add('isVIP')
            ->add('isContactable')
            ->add('person')
            ->add('label')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonLabel::class,
        ]);
    }
}
