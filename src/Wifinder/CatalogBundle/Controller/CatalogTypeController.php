<?php

namespace Wifinder\CatalogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\CatalogBundle\Entity\CatalogType;
use Wifinder\CatalogBundle\Form\CatalogTypeType;

/**
 * CatalogType controller.
 *
 * @Route("/catalog_type")
 */
class CatalogTypeController extends Controller
{
    /**
     * Lists all CatalogType entities.
     *
     * @Route("/", name="catalog_type")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CatalogBundle:CatalogType')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a CatalogType entity.
     *
     * @Route("/{id}/show", name="catalog_type_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatalogBundle:CatalogType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatalogType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new CatalogType entity.
     *
     * @Route("/new", name="catalog_type_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CatalogType();
        $form   = $this->createForm(new CatalogTypeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new CatalogType entity.
     *
     * @Route("/create", name="catalog_type_create")
     * @Method("POST")
     * @Template("CatalogBundle:CatalogType:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new CatalogType();
        $form = $this->createForm(new CatalogTypeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('catalog_type_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CatalogType entity.
     *
     * @Route("/{id}/edit", name="catalog_type_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatalogBundle:CatalogType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatalogType entity.');
        }

        $editForm = $this->createForm(new CatalogTypeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing CatalogType entity.
     *
     * @Route("/{id}/update", name="catalog_type_update")
     * @Method("POST")
     * @Template("CatalogBundle:CatalogType:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatalogBundle:CatalogType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CatalogType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CatalogTypeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('catalog_type_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a CatalogType entity.
     *
     * @Route("/{id}/delete", name="catalog_type_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatalogBundle:CatalogType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CatalogType entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('catalog_type'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
