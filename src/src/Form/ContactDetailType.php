<?php

namespace App\Form;

use App\Entity\ContactDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('telephoneNumber')
            ->add('fax')
            ->add('internetSite')
            ->add('email')
            ->add('salutation')
            ->add('firstName')
            ->add('lastName')
            ->add('is_blacklisted')
            ->add('is_contactable')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDetail::class,
        ]);
    }
}
