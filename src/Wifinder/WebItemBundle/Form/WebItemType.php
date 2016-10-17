<?php

namespace Wifinder\WebItemBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WebItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active')
            ->add('alias')
            ->add('description')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\WebItemBundle\Entity\WebItem",
                'fields' => array(
                    'content' => array(
                        'label' => 'Content',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
                    )
                )
            ))
            ->add('join_contents', 'entity', array(
                'required'  => false,
                'multiple'  => true,
                'class'     => 'PageBundle:Content',
                'attr' => array('class' => 'multiselect')
            ))
            ->add('join_catalogs', 'entity', array(
                'required'  => false,
                'multiple'  => true,
                'class'     => 'CatalogBundle:Catalog',
                'attr' => array('class' => 'multiselect')
            ))
            ->add('join_projects', 'entity', array(
                'required'  => false,
                'multiple'  => true,
                'class'     => 'ProjectBundle:Project',
                'attr' => array('class' => 'multiselect')
            ))
            ->add('action', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('WebItem'),
            'data_class' => 'Wifinder\WebItemBundle\Entity\WebItem'
        ));
    }

    public function getName()
    {
        return 'wifinder_webitembundle_webitemtype';
    }
}
