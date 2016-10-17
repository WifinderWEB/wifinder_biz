<?php

namespace Wifinder\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentMetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\PageBundle\Entity\ContentMeta",
                'fields' => array(
                    'meta_title' => array(
                        'label' => 'Meta title'
                    ),
                    'meta_keywords' => array(
                        'label' => 'Meta keywords'
                    ),
                    'meta_description' => array(
                        'label' => 'Meta description'
                    ),
                ),
                'label' => ' '
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wifinder\PageBundle\Entity\ContentMeta'
        ));
    }

    public function getName()
    {
        return 'wifinder_pagebundle_contentmetatype';
    }
}
