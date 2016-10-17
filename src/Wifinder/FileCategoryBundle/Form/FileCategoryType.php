<?php

namespace Wifinder\FileCategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\FileCategoryBundle\Entity\FileCategory",
                'fields' => array(
                    'name' => array(
                        'label' => 'Title'
                    )
                )
            ))
            ->add('sort')
            ->add('is_active')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wifinder\FileCategoryBundle\Entity\FileCategory'
        ));
    }

    public function getName()
    {
        return 'wifinder_filecategorybundle_filecategorytype';
    }
}
