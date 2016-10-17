<?php

namespace Wifinder\LayoutBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\LayoutBundle\Entity\Layout;
use Wifinder\LayoutBundle\Form\LayoutType;


class LayoutController extends Controller
{
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('admin_layout_page', $page);
        }
        elseif($request->getSession()->has('admin_layout_page')){
            $page = $request->getSession()->get('admin_layout_page');
        }
        else{
            $page = 1;
        }
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('LayoutBundle:Layout')
                ->retriveLayout($request);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            20/*limit per page*/
        );

        $deleteForm = $this->createEmptyDeleteForm();
        
        return $this->render('LayoutBundle:Layout:index.html.twig', array(
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView()
        ));
    }

    public function newAction(Request $request)
    {
        $entity = new Layout();
        $form   = $this->createForm(new LayoutType(), $entity);

        $page = 1;
        if($request->getSession()->has('admin_layout_page')){
            $page = $request->getSession()->get('admin_layout_page');
        }
        
        return $this->render('LayoutBundle:Layout:new.html.twig', array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function createAction(Request $request)
    {
        $entity  = new Layout();
        $form = $this->createForm(new LayoutType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Entry is successfully saved!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_layout'));
            
            return $this->redirect($this->generateUrl('admin_layout_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        $page = 1;
        if($request->getSession()->has('admin_layout_page')){
            $page = $request->getSession()->get('admin_layout_page');
        }
        return $this->render('LayoutBundle:Layout:new.html.twig', array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_layout_page')){
            $page = $request->getSession()->get('admin_layout_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LayoutBundle:Layout')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_layout', array('page' => $page)));
        }

        $editForm = $this->createForm(new LayoutType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('LayoutBundle:Layout:edit.html.twig', array(
            'page'        => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_layout_page')){
            $page = $request->getSession()->get('admin_layout_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LayoutBundle:Layout')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_layout', array('page' => $page)));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LayoutType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('"%name%" is successfully updated!', array('%name%' => $entity->getName()))
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_layout'));
            
            return $this->redirect($this->generateUrl('admin_layout_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        return $this->render('LayoutBundle:Layout:edit.html.twig', array(
            'page'        => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_layout_page')){
            $page = $request->getSession()->get('admin_layout_page');
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LayoutBundle:Layout')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin',
                    $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_layout', array('page' => $page)));
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('"%name%" is successfully deleted!', array('%name%' => $entity->getName()))
            );
        }
        else{
            $this->get('session')->getFlashBag()->add('error',
                $this->get('translator')->trans('Error deleting "%name%".', array('%name%' => $entity->getName()))
            );
        }

        return $this->redirect($this->generateUrl('admin_layout', array('page' => $page)));
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
