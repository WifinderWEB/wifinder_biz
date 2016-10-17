<?php

namespace Wifinder\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wifinder\MenuBundle\Entity\Repository\MenuItemRepository;

class MenuItemType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $menuId = $options['data']->getMenu()->getId();
        $builder
            ->add('is_active')
            ->add('parent', 'entity', array(
                'class' => 'Wifinder\MenuBundle\Entity\MenuItem',
                'query_builder' => function(MenuItemRepository $er) use ($menuId) {
                    return $er->GetItemForMenu($menuId);
                },
                'required' => true, 
                'empty_value' => false
            ))
            ->add('alias')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\MenuBundle\Entity\MenuItem",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    )
                ),
                'label' => ' '
            ))
            ->add('link')
            ->add('action', 'hidden')
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'validation_groups' => array('MenuItem'),
            'data_class' => 'Wifinder\MenuBundle\Entity\MenuItem'
        ));
    }

    public function getName() {
        return 'wifinder_menubundle_menuitemtype';
    }

}
