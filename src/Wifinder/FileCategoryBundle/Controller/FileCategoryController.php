<?php

namespace Wifinder\FileCategoryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\FileCategoryBundle\Entity\FileCategory;
use Wifinder\FileCategoryBundle\Form\FileCategoryType;

/**
 * FileCategory controller.
 *
 * @Route("/file_category")
 */
class FileCategoryController extends Controller
{
    /**
     * Lists all FileCategory entities.
     *
     * @Route("/", name="file_category")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FileCategoryBundle:FileCategory')->findAll();

        return array(
            'entities' => $entities,
        );
    }


    /**
     * Displays a form to create a new FileCategory entity.
     *
     * @Route("/new", name="file_category_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FileCategory();
        $form   = $this->createForm(new FileCategoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FileCategory entity.
     *
     * @Route("/create", name="file_category_create")
     * @Method("POST")
     * @Template("FileCategoryBundle:FileCategory:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FileCategory();
        $form = $this->createForm(new FileCategoryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('file_category', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FileCategory entity.
     *
     * @Route("/{id}/edit", name="file_category_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FileCategoryBundle:FileCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileCategory entity.');
        }

        $editForm = $this->createForm(new FileCategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FileCategory entity.
     *
     * @Route("/{id}/update", name="file_category_update")
     * @Method("POST")
     * @Template("FileCategoryBundle:FileCategory:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FileCategoryBundle:FileCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FileCategoryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('file_category', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FileCategory entity.
     *
     * @Route("/{id}/delete", name="file_category_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FileCategoryBundle:FileCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FileCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('file_category'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
