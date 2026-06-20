<?php

namespace App\Form;

use App\Entity\Member;
use App\Repository\MemberRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class MemberAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Member::class,

            'placeholder' => 'جستجوی کد ملی یا نام...',

            'attr' => ['class' => 'form-control'],

            'min_characters' => 3,

            'preload' => false,

            'searchable_fields' => [
                'firstName',
                'lastName',
                'nationalCode',
            ],

            'choice_label' => fn(Member $member) => sprintf(
                '%s %s (%s)',
                $member->getFirstName(),
                $member->getLastName(),
                $member->getNationalCode()
            ),

            'query_builder' => fn(MemberRepository $repository) =>
            $repository->createQueryBuilder('m')
                ->orderBy('m.lastName', 'ASC'),

            'tom_select_options' => [
                'loadThrottle' => 300,
                'openOnFocus' => false,
                'plugins' => [
                    'clear_button' => false,
                ],
            ],
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
