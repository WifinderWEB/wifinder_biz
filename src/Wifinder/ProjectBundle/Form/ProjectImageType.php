<?php

namespace Wifinder\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image')
            ->add('sort')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\ProjectBundle\Entity\ProjectImage",
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
            'data_class' => 'Wifinder\ProjectBundle\Entity\ProjectImage'
        ));
    }

    public function getName()
    {
        return 'wifinder_projectbundle_projectimagetype';
    }
}
