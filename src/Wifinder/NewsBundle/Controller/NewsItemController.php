<?php

namespace Wifinder\NewsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Wifinder\NewsBundle\Entity\NewsItem;
use Wifinder\NewsBundle\Form\NewsItemType;

/**
 * NewsItem controller.
 *
 */
class NewsItemController extends Controller
{
    /**
     * Lists all NewsItem entities.
     *
     */
    public function indexAction(Request $request, $category_id)
    {
        $news = $this->getDoctrine()->getManager()->getRepository('NewsBundle:NewsCategory')->find($category_id);
        if(!$news){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find news category with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id)));
        }
        
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('admin_news_item_page', $page);
        }
        elseif($request->getSession()->has('admin_news_item_page')){
            $page = $request->getSession()->get('admin_news_item_page');
        }
        else{
            $page = 1;
        }
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('NewsBundle:NewsItem')
                ->retriveNewsItem($category_id);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            20/*limit per page*/
        );
        
        $deleteForm = $this->createEmptyDeleteForm();
        
        return $this->render('NewsBundle:NewsItem:index.html.twig', array(
            'category_id' => $category_id,
            'pagination'  => $pagination,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to create a new NewsItem entity.
     *
     */
    public function newAction(Request $request, $category_id)
    {
        $news = $this->getDoctrine()->getManager()->getRepository('NewsBundle:NewsCategory')->find($category_id);
        if(!$news){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find news category with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id)));
        }
        
        $entity = new NewsItem();
        $entity->setCategory($news);
        $form   = $this->createForm(new NewsItemType(), $entity);

        return $this->render('NewsBundle:NewsItem:new.html.twig', array(
            'category_id' => $category_id,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new NewsItem entity.
     *
     */
    public function createAction(Request $request, $category_id)
    {
        $news = $this->getDoctrine()->getManager()->getRepository('NewsBundle:NewsCategory')->find($category_id);
        if(!$news){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find news category with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id)));
        }
        
        $entity  = new NewsItem();
        $form = $this->createForm(new NewsItemType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('News Item is successfully saved!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id, 'id' => $entity->getId())));
            
            return $this->redirect($this->generateUrl('admin_news_item_edit', array('id' => $entity->getId(), 'category_id' => $category_id)));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );

        return $this->render('NewsBundle:NewsItem:new.html.twig', array(
            'category_id' => $category_id,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing NewsItem entity.
     *
     */
    public function editAction($category_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('NewsBundle:NewsCategory')->find($category_id);
        if(!$news){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find news category with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_news_category'));
        }

        $entity = $em->getRepository('NewsBundle:NewsItem')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find news item with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id)));
        }

        $editForm = $this->createForm(new NewsItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NewsBundle:NewsItem:edit.html.twig', array(
            'category_id' => $category_id,
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing NewsItem entity.
     *
     */
    public function updateAction(Request $request, $category_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('NewsBundle:NewsCategory')->find($category_id);
        if(!$news){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find news category with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_news_category'));
        }

        $entity = $em->getRepository('NewsBundle:NewsItem')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find news item with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id)));
        }

        $originalImages = array();
        foreach ($entity->getImages() as $image) {
            $originalImages[] = $image;
        }
        
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new NewsItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            foreach ($entity->getImages() as $image) {
                foreach ($originalImages as $key => $toDel) {
                    if ($toDel->getId() === $image->getId()) {
                        unset($originalImages[$key]);
                    }
                }
            }
            foreach ($originalImages as $image) {
                $em->remove($image);
            }
            
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('News Item "%title%" is successfully updated!', array('%title%' => $entity->getTitle()))
            );
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id)));
            
            return $this->redirect($this->generateUrl('admin_news_item_edit', array('id' => $entity->getId(), 'category_id' => $category_id)));
        }

        $this->get('session')->getFlashBag()->add('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );

        return $this->render('NewsBundle:NewsItem:edit.html.twig', array(
            'category_id' => $category_id,
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a NewsItem entity.
     *
     */
    public function deleteAction(Request $request, $category_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('NewsBundle:NewsCategory')->find($category_id);
        if(!$news){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find news category with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_news_category'));
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NewsBundle:NewsItem')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin',
                    $this->get('translator')->trans('Unable to find news item with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id)));
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('News Item "%title%" is successfully deleted!', array('%title%' => $entity->getTitle()))
            );
        }
        else{
            $this->get('session')->getFlashBag()->add('error',
                $this->get('translator')->trans('Error deleting "%title%".', array('%title%' => $entity->getTitle()))
            );
        }

        return $this->redirect($this->generateUrl('admin_news_item', array('category_id' => $category_id)));
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
            $entity = $em->getRepository('NewsBundle:NewsItem')->find($id);

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
                        'NewsBundle:NewsItem:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }
}
