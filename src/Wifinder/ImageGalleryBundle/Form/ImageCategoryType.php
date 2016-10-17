<?php

namespace Wifinder\ImageGalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('is_active')
        ->add('alias')
        ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\ImageGalleryBundle\Entity\ImageCategory",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'description' => array(
                        'label' => 'Description',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
                    )
                 )
            ))
            ->add('action', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('imageCategory'),
            'data_class' => 'Wifinder\ImageGalleryBundle\Entity\ImageCategory'
        ));
    }

    public function getName()
    {
        return 'wifinder_imagegallerybundle_imagecategorytype';
    }
}
