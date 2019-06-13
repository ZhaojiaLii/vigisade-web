<?php

namespace App\Form;

use App\Entity\SurveyCategory;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyCategoryEmbeddedForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,  [
                'label' => 'Titre Rubrique : '
            ])
            ->add('questions', CollectionType::class, [
                'entry_type' => SurveyQuestionEmbeddedForm::class,
                'allow_delete' => true,
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SurveyCategory::class
        ]);
    }
}
