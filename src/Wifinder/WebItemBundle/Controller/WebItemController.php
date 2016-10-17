<?php

namespace Wifinder\WebItemBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\WebItemBundle\Entity\WebItem;
use Wifinder\WebItemBundle\Form\WebItemType;


class WebItemController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('WebItemBundle:WebItem')->retriveWebItem($request);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, 
                $this->get('request')->query->get('page', 1)/* page number */,
                10/* limit per page */
        );
        
        $deleteForm = $this->createEmptyDeleteForm();
        
        return array(
            'delete_form' => $deleteForm->createView(),
            'pagination' => $pagination,
        );
    }

    /**
     * @Template()
     */
    public function newAction()
    {
        $entity = new WebItem();
        $form   = $this->createForm(new WebItemType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template("WebItemBundle:WebItem:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new WebItem();
        $form = $this->createForm(new WebItemType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Web Item "%alias%" is successfully saved!', array('%alias%' => $entity->getAlias()))
            );
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_web_item'));
            
            return $this->redirect($this->generateUrl('admin_web_item_edit', array('id' => $entity->getId())));
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
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WebItemBundle:WebItem')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find Web Item with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_web_item'));
        }

        $editForm = $this->createForm(new WebItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("WebItemBundle:WebItem:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WebItemBundle:WebItem')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find Web Item with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_web_item'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new WebItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Web Item "%alias%" is successfully updated!', array('%alias%' => $entity->getAlias()))
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_web_item'));
            
            return $this->redirect($this->generateUrl('admin_web_item_edit', array('id' => $entity->getId())));
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

    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WebItemBundle:WebItem')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin',
                    $this->get('translator')->trans('Unable to find Web Item with id = "%id%"', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_web_item'));
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Web Item "%alias%" is successfully deleted!', array('%alias%' => $entity->getAlias()))
            );
        }
        else{
            $this->get('session')->getFlashBag()->add('error',
                $this->get('translator')->trans('Error deleting Web Item "%alias%".', array('%alias%' => $entity->getAlias()))
            );
        }

        return $this->redirect($this->generateUrl('admin_web_item'));
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
}
