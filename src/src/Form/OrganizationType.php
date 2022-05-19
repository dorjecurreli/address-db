<?php

namespace App\Form;

use App\Entity\Organization;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address', AddressType::class)
            ->add('projects', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'multiple' => true,
                'mapped' => true,
            ])
            ->add('organizationType', OrganizationTypeType::class )
            ->add('contactDetail', ContactDetailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organization::class,
        ]);
    }
}
