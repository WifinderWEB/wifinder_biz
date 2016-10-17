<?php

namespace Wifinder\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active', 'checkbox', array('required' => false))
            ->add('years', 'entity', array(
                'class' => 'ProjectBundle:Years',
                'property' => 'alias',
                'required' => true,
                'empty_value' => " "
            ))
            ->add('sort')
            ->add('alias')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\ProjectBundle\Entity\Project",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'short_description' => array(
                        'label' => 'Short description',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
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
            ->add('images', 'collection', array(
                'type' => new ProjectImageType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
                ))
            ->add('files', 'collection', array(
                'type' => new ProjectFileType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
                ))
            ->add('meta', new ProjectMetaType())
            ->add('action', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Project'),
            'data_class' => 'Wifinder\ProjectBundle\Entity\Project'
        ));
    }

    public function getName()
    {
        return 'wifinder_projectbundle_projecttype';
    }
}
