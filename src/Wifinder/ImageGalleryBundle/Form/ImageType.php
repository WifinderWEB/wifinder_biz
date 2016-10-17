<?php

namespace Wifinder\ImageGalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active')
            ->add('image')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\ImageGalleryBundle\Entity\Image",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'description' => array(
                        'label' => 'Description',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF',
                            'toolbar' => array(
                                array(
                                    'name' => 'document',
                                    'items' => array('Source')
                                ),
                                array(
                                    'name' => 'basicstyles',
                                    'items' => array('Bold','Italic','Underline','Subscript','Superscript','-','RemoveFormat')
                                ),
                                array(
                                    'name' => 'paragraph',
                                    'items' => array('NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock')
                                )
                            )
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
            'data_class' => 'Wifinder\ImageGalleryBundle\Entity\Image'
        ));
    }

    public function getName()
    {
        return 'wifinder_imagegallerybundle_imagetype';
    }
}
