<?php

namespace Wifinder\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatalogImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\CatalogBundle\Entity\CatalogImage",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'description' => array(
                        'label' => 'Description',
                        'field_type' => 'textarea'
                    )
                ),
                'label' => ' '
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wifinder\CatalogBundle\Entity\CatalogImage'
        ));
    }

    public function getName()
    {
        return 'wifinder_catalogbundle_catalogimagetype';
    }
}
