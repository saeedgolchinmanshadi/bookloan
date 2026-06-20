<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Publisher;
use App\Entity\Subject;
use App\Repository\SubjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'عنوان کتاب',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control', 'placeholder' => 'مثال: سمفونی مردگان']
            ])
            ->add('author', TextType::class, [
                'label' => 'نویسنده',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control', 'placeholder' => 'نام نویسنده کتاب']
            ])
            ->add('publisher', EntityType::class, [
                'class' => Publisher::class,
                'choice_label' => 'name',
                'label' => 'ناشر',
                'placeholder' => 'ناشر را انتخاب کنید...',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select']
            ])
            ->add('subjects', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'title',
                'multiple' => true,
                'label' => 'موضوعات کتاب',
                'label_attr' => ['class' => 'form-label'],
                'expanded' => true,
                'query_builder' => fn(SubjectRepository $repository) => $repository->createQueryBuilder('s')
                    ->orderBy('s.title', 'ASC'),
                'attr' => ['class' => 'subject-chips'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}