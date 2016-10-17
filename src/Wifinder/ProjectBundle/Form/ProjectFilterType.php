<?php

namespace Wifinder\ProjectBundle\Form;

use \Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wifinder\ProjectBundle\Entity\Repository\ProjectRepository;

class ProjectFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias')
            ->add('title')
            ->add('years', 'entity', array(
                'class' => 'ProjectBundle:Years',
                'property' => 'alias',
                'required' => true,
                'empty_value' => " "
            ))
            ->add('is_active', 'choice', array(
                'choices' => array(0 => 'No',1 => 'Yes'),
                'empty_value' => 'Yes or No',
                'required' => false))
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
        return 'wifinder_projectbundle_projectfiltertype';
    }
}
