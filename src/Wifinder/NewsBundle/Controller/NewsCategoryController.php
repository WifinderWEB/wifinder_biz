<?php

namespace Wifinder\NewsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Wifinder\NewsBundle\Entity\NewsCategory;
use Wifinder\NewsBundle\Form\NewsCategoryType;

/**
 * NewsCategory controller.
 *
 */
class NewsCategoryController extends Controller
{
    /**
     * Lists all NewsCategory entities.
     *
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('admin_news_category_page', $page);
        }
        elseif($request->getSession()->has('admin_news_category_page')){
            $page = $request->getSession()->get('admin_news_category_page');
        }
        else{
            $page = 1;
        }
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('NewsBundle:NewsCategory')
                ->retriveNewsCategory($request);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            20/*limit per page*/
        );

        $deleteForm = $this->createEmptyDeleteForm();
        
        return $this->render('NewsBundle:NewsCategory:index.html.twig', array(
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to create a new NewsCategory entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new NewsCategory();
        $form   = $this->createForm(new NewsCategoryType(), $entity);

        $page = 1;
        if($request->getSession()->has('admin_news_category_page')){
            $page = $request->getSession()->get('admin_news_category_page');
        }
        return $this->render('NewsBundle:NewsCategory:new.html.twig', array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new NewsCategory entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new NewsCategory();
        $form = $this->createForm(new NewsCategoryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Entry is successfully saved!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_news_category'));
            
            return $this->redirect($this->generateUrl('admin_news_category_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        $page = 1;
        if($request->getSession()->has('admin_news_category_page')){
            $page = $request->getSession()->get('admin_news_category_page');
        }
        return $this->render('NewsBundle:NewsCategory:new.html.twig', array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing NewsCategory entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_news_category_page')){
            $page = $request->getSession()->get('admin_news_category_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('NewsBundle:NewsCategory')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_news_category', array('page' => $page)));
        }

        $editForm = $this->createForm(new NewsCategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NewsBundle:NewsCategory:edit.html.twig', array(
            'page'        => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing NewsCategory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_news_category_page')){
            $page = $request->getSession()->get('admin_news_category_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('NewsBundle:NewsCategory')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_news_category', array('page' => $page)));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new NewsCategoryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('"%title%" is successfully updated!', array('%title%' => $entity->getTitle()))
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_news_category'));
            
            return $this->redirect($this->generateUrl('admin_news_category_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        return $this->render('NewsBundle:NewsCategory:edit.html.twig', array(
            'page'        => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a NewsCategory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_news_category_page')){
            $page = $request->getSession()->get('admin_news_category_page');
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NewsBundle:NewsCategory')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin',
                    $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_news_category', array('page' => $page)));
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

        return $this->redirect($this->generateUrl('admin_news_category', array('page' => $page)));
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
            $entity = $em->getRepository('NewsBundle:NewsCategory')->find($id);

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
                        'NewsBundle:NewsCategory:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }
}
