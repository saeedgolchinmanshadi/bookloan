<?php

namespace App\Form;

use App\Entity\BookLoan;
use App\Form\BookAutocompleteField;
use App\Form\MemberAutocompleteField;
use Morilog\Jalali\Jalalian;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookLoanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('book', BookAutocompleteField::class, [
                'label' => 'انتخاب کتاب',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('member', MemberAutocompleteField::class, [
                'label' => 'عضو امانت گیرنده / رزرو کننده',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'امانت' => 'loan',
                    'رزرو' => 'reservation',
                ],
                'expanded' => true,
                'label' => 'نوع درخواست',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'loan-type-cards'],
            ])
            ->add('borrowDate', TextType::class, [
                'label' => 'تاریخ ثبت / تحویل',
                'label_attr' => ['class' => 'form-label mb-2'],
                'attr' => [
                    'class' => 'form-control date-mask mt-2 fw-bold text-center',
                    'placeholder' => '1402/05/12',
                    'autocomplete' => 'off',
                    'dir' => 'ltr',
                ],
            ])
            ->add('dueDate', TextType::class, [
                'label' => 'مهلت بازگشت (اختیاری برای رزرو)',
                'required' => false,
                'label_attr' => ['class' => 'form-label mb-2'],
                'attr' => [
                    'class' => 'form-control date-mask mt-2 fw-bold text-center',
                    'placeholder' => '1402/05/19',
                    'autocomplete' => 'off',
                    'dir' => 'ltr',
                ],
            ])
        ;

        $builder->get('borrowDate')->addModelTransformer($this->createJalaliTransformer(required: true));
        $builder->get('dueDate')->addModelTransformer($this->createJalaliTransformer(required: false));
    }

    private function createJalaliTransformer(bool $required): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($dateAsObject) {
                if (!$dateAsObject) {
                    return '';
                }

                $timezone = new \DateTimeZone('Asia/Tehran');
                $localDateTime = \DateTimeImmutable::createFromInterface($dateAsObject)->setTimezone($timezone);

                return Jalalian::fromDateTime($localDateTime)->format('Y/m/d');
            },
            function ($dateAsString) use ($required) {
                if (!$dateAsString) {
                    if ($required) {
                        throw new TransformationFailedException('وارد کردن تاریخ الزامی است.');
                    }

                    return null;
                }

                try {
                    $carbonDate = Jalalian::fromFormat('Y/m/d', $dateAsString)->toCarbon();
                    $carbonDate->setTimezone(new \DateTimeZone('Asia/Tehran'));

                    return \DateTimeImmutable::createFromMutable($carbonDate);
                } catch (\Exception) {
                    throw new TransformationFailedException('فرمت تاریخ نامعتبر است. مثال: 1402/05/12');
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookLoan::class,
        ]);
    }
}