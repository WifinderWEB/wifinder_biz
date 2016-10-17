<?php

namespace Wifinder\CatalogBundle\Form;

use \Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wifinder\CatalogBundle\Entity\Repository\CatalogRepository;

class CatalogFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', 'entity', array(
                'class' => 'CatalogBundle:Catalog',
                'query_builder' => function(CatalogRepository $er) {
                    return $er->GetCategoriesQuery();
                },
                'required' => false, 
                'empty_value' => ' '
            ))
            ->add('catalog_type', 'entity', array(
                'class' => 'CatalogBundle:CatalogType',
                'property' => 'name',
                'required' => false,
                'empty_value' => ' '
            ))
            ->add('alias')
            ->add('title')
            ->add('is_active', 'choice', array(
                'choices' => array(0 => 'No',1 => 'Yes'),
                'empty_value' => 'Yes or No',
                'required' => false))
            ;
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Catalog'),
            'data_class' => 'Wifinder\CatalogBundle\Entity\Catalog'
        ));
    }

    public function getName()
    {
        return 'wifinder_catalogbundle_catalogfiltertype';
    }
}
