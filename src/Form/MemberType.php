<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'نام',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'نام خانوادگی',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('nationalCode', TextType::class, [
                'label' => 'کد ملی',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control', 'maxlength' => 10]
            ])
            ->add('mobile', TextType::class, [
                'label' => 'تلفن همراه',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control', 'maxlength' => 11]
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'حساب کاربری فعال باشد',
                'required' => false,
                'label_attr' => ['class' => 'form-label me-2 mb-0 d-inline-block'],
                'attr' => ['class' => 'form-check-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}