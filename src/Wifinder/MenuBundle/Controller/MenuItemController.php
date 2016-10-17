<?php

namespace Wifinder\MenuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\MenuBundle\Entity\MenuItem;
use Wifinder\MenuBundle\Form\MenuItemType;

class MenuItemController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction($menu_id)
    {
        $menu = $this->getDoctrine()->getManager()->getRepository('MenuBundle:Menu')->find($menu_id);
        if(!$menu){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu with id = "%id%".', array('%id%' => $menu_id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }
        
        $em = $this->getDoctrine()->getRepository('MenuBundle:MenuItem');
        $entities = $em->GetItemsByMenuId($menu_id);
        
        $deleteForm = $this->createEmptyDeleteForm();
        
        if($test = $em->verify()){
            return array(
                'entities' => $entities,
                'menu_id' => $menu_id,
                'delete_form' => $deleteForm->createView()
            );
        }
        else
            var_dump($test); exit;
    }

    /**
     * @Template()
     */
    public function newAction($menu_id)
    {
        $menu = $this->getDoctrine()->getManager()->getRepository('MenuBundle:Menu')->find($menu_id);
        if(!$menu){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu with id = "%id%".', array('%id%' => $menu_id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }
        $entity = new MenuItem();
        $entity->setMenu($menu);

        $form   = $this->createForm(new MenuItemType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template("MenuBundle:MenuItem:new.html.twig")
     */
    public function createAction(Request $request, $menu_id)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('MenuBundle:Menu')->find($menu_id);
        if(!$menu){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu with id = "%id%".', array('%id%' => $menu_id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }
        
        $entity  = new MenuItem();
        $entity->setMenu($menu);
        $form = $this->createForm(new MenuItemType(), $entity);
        
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Menu Item is successfully saved!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_menu_item', array('menu_id' => $menu_id)));
            
            return $this->redirect($this->generateUrl('admin_menu_item_edit', array('id' => $entity->getId(), 'menu_id' => $menu_id)));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template()
     */
    public function editAction($menu_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('MenuBundle:Menu')->find($menu_id);
        if(!$menu){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu with id = "%id%".', array('%id%' => $menu_id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }

        $entity = $em->getRepository('MenuBundle:MenuItem')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu item with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_menu_item', array('menu_id' => $menu_id)));
        }

        $editForm = $this->createForm(new MenuItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("MenuBundle:MenuItem:edit.html.twig")
     */
    public function updateAction(Request $request, $menu_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('MenuBundle:Menu')->find($menu_id);
        if(!$menu){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu with id = "%id%".', array('%id%' => $menu_id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }

        $entity = $em->getRepository('MenuBundle:MenuItem')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu item with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_menu_item', array('menu_id' => $menu_id)));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MenuItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Menu Item "%title%" is successfully updated!', array('%title%' => $entity->getTitle()))
            );
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_menu_item', array('menu_id' => $menu_id)));
            
            return $this->redirect($this->generateUrl('admin_menu_item_edit', array('id' => $entity->getId(), 'menu_id' => $menu_id)));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function deleteAction(Request $request, $menu_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('MenuBundle:Menu')->find($menu_id);
        if(!$menu){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu with id = "%id%".', array('%id%' => $menu_id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $entity = $em->getRepository('MenuBundle:MenuItem')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin',
                    $this->get('translator')->trans('Unable to find menu item with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_menu_item', array('menu_id' => $menu_id)));
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Menu Item "%title%" is successfully deleted!', array('%title%' => $entity->getTitle()))
            );
        }
        else{
            $this->get('session')->getFlashBag()->add('error',
                $this->get('translator')->trans('Error deleting "%title%".', array('%title%' => $entity->getTitle()))
            );
        }

        return $this->redirect($this->generateUrl('admin_menu_item', array('menu_id' => $entity->getMenuId())));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    private function createEmptyDeleteForm() {
        return $this->createFormBuilder()
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    public function changeActiveAction(Request $request) {
        if ($request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $active = $request->request->get('active');
            
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MenuBundle:MenuItem')->find($id);

            if (!$entity) {
                return null;
            }

            if ($active)
                $active = 0;
            else
                $active = 1;

            $entity->setIsActive($active);
            $em->persist($entity);
            $em->flush();
        }
        else{
            $active = "";
        }
        return $this->render(
                        'MenuBundle:MenuItem:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }
    
    public function moveUpAction($menu_id, $id){
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('MenuBundle:Menu')->find($menu_id);
        if(!$menu){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu with id = "%id%".', array('%id%' => $menu_id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }

        $entity = $em->getRepository('MenuBundle:MenuItem')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }
        
        $em->getRepository('MenuBundle:MenuItem')->moveUp($entity);
        
        $this->get('session')->getFlashBag()->add('notice',
            $this->get('translator')->trans('"%title%" is successfully moved up!', array('%title%' => $entity->getTitle()))
        );
        return $this->redirect($this->generateUrl('admin_menu_item', array('menu_id' => $menu_id)));
    }
    
    public function moveDownAction($menu_id, $id){
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('MenuBundle:Menu')->find($menu_id);
        if(!$menu){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find menu with id = "%id%".', array('%id%' => $menu_id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }

        $entity = $em->getRepository('MenuBundle:MenuItem')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }
        
        $em->getRepository('MenuBundle:MenuItem')->moveDown($entity);
        
        $this->get('session')->getFlashBag()->add('notice',
            $this->get('translator')->trans('"%title%" is successfully moved down!', array('%title%' => $entity->getTitle()))
        );
        return $this->redirect($this->generateUrl('admin_menu_item', array('menu_id' => $menu_id)));
    }
}
