<?php

namespace Wifinder\ImageGalleryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\ImageGalleryBundle\Entity\Image;
use Wifinder\ImageGalleryBundle\Form\ImageType;


class ImageController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(Request $request, $category_id)
    {
        $category = $this->getDoctrine()->getManager()->getRepository('ImageGalleryBundle:ImageCategory')->find($category_id);
        if(!$category){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find gallery with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_image_gallery_category'));
        }
        
        $em = $this->getDoctrine()->getRepository('ImageGalleryBundle:Image');
        $entities = $em->GetImagesCategoryId($category_id);
        
        $deleteForm = $this->createEmptyDeleteForm();

        return array(
            'entities' => $entities,
            'category_id' => $category_id,
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * @Template()
     */
    public function newAction($category_id)
    {
        $category = $this->getDoctrine()->getManager()->getRepository('ImageGalleryBundle:ImageCategory')->find($category_id);
        if(!$category){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find gallery with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_image_gallery_category'));
        }
        
        $entity = new Image();
        $form   = $this->createForm(new ImageType(), $entity);

        return array(
            'entity' => $entity,
            'category_id' => $category_id,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template("ImageGalleryBundle:Image:new.html.twig")
     */
    public function createAction(Request $request, $category_id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('ImageGalleryBundle:ImageCategory')->find($category_id);
        if(!$category){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find gallery with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_image_gallery_category'));
        }
        
        $entity  = new Image();
        $form = $this->createForm(new ImageType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setCategory($category);
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Entry is successfully saved!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_image_gallery_index', array('category_id' => $category_id)));
            
            return $this->redirect($this->generateUrl('admin_image_gallery_edit', array('id' => $entity->getId(), 'category_id' => $category_id)));
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
    public function editAction($id, $category_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('ImageGalleryBundle:ImageCategory')->find($category_id);
        if(!$category){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find gallery with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_image_gallery_category'));
        }
        
        $entity = $em->getRepository('ImageGalleryBundle:Image')->find($id);
        
        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find gallery image with id = "%id%".', array('%id%' => $entity->getId()))
            );
            return $this->redirect($this->generateUrl('admin_image_gallery_index', array('$category_id' => $category_id)));
        }

        $editForm = $this->createForm(new ImageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("ImageGalleryBundle:Image:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $category_id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('ImageGalleryBundle:ImageCategory')->find($category_id);
        if(!$category){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find gallery with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_image_gallery_category'));
        }
        
        $entity = $em->getRepository('ImageGalleryBundle:Image')->find($id);
        
        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find gallery image with id = "%id%".', array('%id%' => $entity->getId()))
            );
            return $this->redirect($this->generateUrl('admin_image_gallery_index', array('$category_id' => $category_id)));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ImageType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('"%title%" is successfully updated!', array('%title%' => $entity->getTitle()))
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_image_gallery_index', array('category_id' => $category_id)));
            
            return $this->redirect($this->generateUrl('admin_image_gallery_edit', array('id' => $entity->getId(), 'category_id' => $category_id)));
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

    public function deleteAction(Request $request, $id, $category_id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('ImageGalleryBundle:ImageCategory')->find($category_id);
        if(!$category){
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find gallery with id = "%id%".', array('%id%' => $category_id))
            );
            return $this->redirect($this->generateUrl('admin_image_gallery_category'));
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $entity = $em->getRepository('ImageGalleryBundle:Image')->find($id);
        
            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin',
                    $this->get('translator')->trans('Unable to find gallery image with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_image_gallery_index', array('category_id' => $category_id)));
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
        
        return $this->redirect($this->generateUrl('admin_image_gallery_index', array('category_id' => $category_id)));
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
            $entity = $em->getRepository('ImageGalleryBundle:Image')->find($id);

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
                        'ImageGalleryBundle:Image:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }
}
