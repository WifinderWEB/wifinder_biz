<?php

namespace Wifinder\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsItemImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\NewsBundle\Entity\NewsItemImage",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'description' => array(
                        'label' => 'Description',
                        'type' => 'textarea'
                    )
                ),
                'label' => ' '
            ));
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wifinder\NewsBundle\Entity\NewsItemImage'
        ));
    }

    public function getName()
    {
        return 'wifinder_newsbundle_newsitemimagetype';
    }
}
