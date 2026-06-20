<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class BookAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Book::class,

            'placeholder' => 'جستجوی عنوان یا نویسنده کتاب...',

            'attr' => ['class' => 'form-control'],

            'min_characters' => 3,

            'preload' => false,

            'searchable_fields' => [
                'title',
                'author',
            ],

            'choice_label' => fn(Book $book) => sprintf(
                '%s (اثر: %s)',
                $book->getTitle(),
                $book->getAuthor()
            ),

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
