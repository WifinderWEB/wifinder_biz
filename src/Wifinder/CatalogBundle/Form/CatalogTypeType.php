<?php

namespace Wifinder\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatalogTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\CatalogBundle\Entity\CatalogType",
                'fields' => array(
                    'name' => array(
                        'label' => 'Name'
                    ))
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wifinder\CatalogBundle\Entity\CatalogType'
        ));
    }

    public function getName()
    {
        return 'wifinder_catalogbundle_catalogtypetype';
    }
}
