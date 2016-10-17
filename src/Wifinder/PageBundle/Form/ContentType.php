<?php

namespace Wifinder\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('is_active')
            ->add('layout')
            ->add('alias');
        if($builder->getData()->getShowEditor()){
            $builder->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\PageBundle\Entity\Content",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'content' => array(
                        'label' => 'Content',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF',
                            'filebrowserBrowseRoute' => 'elfinder'
                        )
                    ),
                ),
                'label' => ' '
            ));
        }
        else{
            $builder->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\PageBundle\Entity\Content",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'content' => array(
                        'label' => 'Content',
                        'field_type' => 'textarea'
                    ),
                ),
                'label' => ' '
            ));
        }
        
        $builder->add('show_editor')
            ->add('meta', new ContentMetaType())
            ->add('action', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'validation_groups' => array('Content'),
            'data_class' => 'Wifinder\PageBundle\Entity\Content'
        ));
    }

    public function getName() {
        return 'wifinder_pagebundle_contenttype';
    }

}
