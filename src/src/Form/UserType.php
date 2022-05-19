<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('email')
            //->add('password')
            ->add('address', AddressType::class)

//            ->add('address', CollectionType::class, [
//                'entry_type' => AddressType::class,
//                'entry_options' => [
//                    'attr' => ['class' => ''],
//                ],
//
//            ])
            ->add('ROLES', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ])
        ;


        // Data transformer
//        $builder->get('ROLES')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($rolesArray) {
//                    // transform the array to a string
//
//                    return count($rolesArray)? $rolesArray[0]: null;
//                },
//                function ($rolesString) {
//                    // transform the string back to an array
//                    return [$rolesString];
//                }
//            ));

//        $builder
//            ->add('email')
//            ->add('password')
//            ->add('roles',  ChoiceType::class, [
//                'label' => "LABEL",
//                'choices' => User::roles,
//                'expanded' => true,
//                'multiple' => true,
////
////                //'choices' => User::roles,
//
//
//            ]);

        //TODO: Fix multiple choice
        //dd($builder->get('roles'));


        //roles field data transformer
//        $builder->get('roles')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($rolesArray) {
//                    //dd($rolesArray);
//                    // transform the array to a string
//                    return count($rolesArray)? $rolesArray[0]: null;
//                },
//                function ($rolesString) {
//                    // transform the string back to an array
//                    dd($rolesString);
//
//                    return [$rolesString];
//                }
//            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
