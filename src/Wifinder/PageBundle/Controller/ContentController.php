<?php

namespace Wifinder\PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Wifinder\PageBundle\Entity\Content;
use Wifinder\PageBundle\Form\ContentType;

class ContentController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('admin_content_page', $page);
        }
        elseif($request->getSession()->has('admin_content_page')){
            $page = $request->getSession()->get('admin_content_page');
        }
        else{
            $page = 1;
        }
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('PageBundle:Content')
                ->retriveContent($request);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            20/*limit per page*/
        );

        $deleteForm = $this->createEmptyDeleteForm();
        
        return array(
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new Content();
        $form   = $this->createForm(new ContentType(), $entity);

        $page = 1;
        if($request->getSession()->has('admin_content_page')){
            $page = $request->getSession()->get('admin_content_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template("PageBundle:Content:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Content();
        $form = $this->createForm(new ContentType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Entry is successfully saved!')
            );
            
            $page = $request->getSession()->set('admin_content_page', 1);
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_content'));
            
            return $this->redirect($this->generateUrl('admin_content_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        $page = 1;
        if($request->getSession()->has('admin_content_page')){
            $page = $request->getSession()->get('admin_content_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_content_page')){
            $page = $request->getSession()->get('admin_content_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PageBundle:Content')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_content', array('page' => $page)));
        }

        $editForm = $this->createForm(new ContentType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'page' => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("PageBundle:Content:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_content_page')){
            $page = $request->getSession()->get('admin_content_page');
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PageBundle:Content')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_content', array('page' => $page)));
        }
        
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ContentType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('"%title%" is successfully updated!', array('%title%' => $entity->getTitle()))
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_content'));
            
            return $this->redirect($this->generateUrl('admin_content_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        return array(
            'page' => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function deleteAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_content_page')){
            $page = $request->getSession()->get('admin_content_page');
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PageBundle:Content')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin',
                    $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_content', array('page' => $page)));
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('"%title%" is successfully deleted!', array('%title%' => $entity->getTitle()))
            );
        }
        else{
            $this->get('session')->getFlashBag()->add('error',
                $this->get('translator')->trans('Error deleting "%title%".', array('%title%' => $entity->getTitle()))
            );
        }

        return $this->redirect($this->generateUrl('admin_content', array('page' => $page)));
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
            $entity = $em->getRepository('PageBundle:Content')->find($id);

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
                        'PageBundle:content:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }
}
