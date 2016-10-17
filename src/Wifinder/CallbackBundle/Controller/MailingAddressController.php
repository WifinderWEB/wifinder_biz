<?php

namespace Wifinder\CallbackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\CallbackBundle\Entity\MailingAddress;

use Wifinder\CallbackBundle\Form\MailingAddressType;
use Wifinder\CallbackBundle\Form\MailingAddressFilterType;

/**
 * MailingAddress controller.
 *
 */
class MailingAddressController extends Controller {

    private $query;
    private $metadata;
    private static $current_class;
    
    /**
     * @Template()
     */
    public function indexAction(Request $request) {
        $page = $request->query->get('page');

        if ($page) {
            $request->getSession()->set('admin_mailing_page', $page);
        } elseif ($request->getSession()->has('admin_mailing_page')) {
            $page = $request->getSession()->get('admin_mailing_page');
        } else {
            $page = 1;
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $this->BuildQuery($request), $page, /* page number */ 20/* limit per page */
        );
        $deleteForm = $this->createEmptyDeleteForm();

        $filterForm = $this->createForm(new MailingAddressFilterType(), $this->getFilterModel($request));

        return array(
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView(),
            'filter_form' => $filterForm->createView()
        );
    }

    public function filterAction(Request $request) {
        $form = $this->createForm(new MailingAddressFilterType());
        $form->bind($request);

        $this->setFilter($request, $form);
        $request->getSession()->set('admin_mailing_page', 1);

        return $this->redirect($this->generateUrl('admin_mailingaddress'));
    }

    public function resetFilterAction(Request $request) {
        $request->getSession()->remove('admin_mailing_filter');
        $request->getSession()->set('admin_mailing_page', 1);

        return $this->redirect($this->generateUrl('admin_mailingaddress'));
    }

    private function BuildQuery($request) {
        $em = $this->getDoctrine()->getManager();
        $this->query = $em->getRepository('CallbackBundle:MailingAddress')
                ->retriveMailingAddress($request);

        $this->getFilter($request);

        return $this->query->getQuery()->setHint(
                        \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')->execute();
    }

    private function setFilter($request, $form) {
        $filter = array();
        foreach ($form as $one) {
            if ($one->getData() !== null) {
                $type = $this->getDbType('Wifinder\CallbackBundle\Entity\MailingAddress', $one->getName());
                if ($type == 'entity')
                    $filter[$one->getName()] = array('type' => $type, 'value' => $one->getData()->getId());
                else
                    $filter[$one->getName()] = array('type' => $type, 'value' => $one->getData());
            }
        }
        $request->getSession()->set('admin_mailing_filter', $filter);
    }

    private function getFilter($request) {
        if ($request->getSession()->has('admin_mailing_filter')) {
            foreach ($request->getSession()->get('admin_mailing_filter') as $i => $one) {
                call_user_func_array(array($this, 'add' . ucfirst($one['type']) . 'Filter'), array($i, $one['value']));
            }
        }
    }

    private function getFilterModel($request) {
        $entity = new MailingAddress();
        $entity->setIsActive(null);
        if ($request->getSession()->has('admin_mailing_filter')) {
            foreach ($request->getSession()->get('admin_mailing_filter') as $i => $one) {
                if ($one['type'] == 'entity' || $one['type'] == 'collection') {
                    $metadata = $this->getMetadatas('Wifinder\CallbackBundle\Entity\MailingAddress');
                    $targetClass = $metadata->getAssociationTargetClass($i);
                    $one['value'] = $this->getDoctrine()->getManager()->getRepository($targetClass)->find($one['value']);
                }
                $entity->set($i, $one['value']);
            }
        }
        return $entity;
    }

    private function addBooleanFilter($field, $value) {
        if ("" !== $value) {
            $this->query->andWhere(sprintf('c.%s = :%s', $field, $field));
            $this->query->setParameter($field, $value);
        }
    }

    private function addStringFilter($field, $value) {
        $this->query->andWhere(sprintf('c.%s LIKE :%s', $field, $field));
        $this->query->setParameter($field, '%' . $value . '%');
    }

    private function addTextFilter($field, $value) {
        $this->query->andWhere(sprintf('c.%s LIKE :%s', $field, $field));
        $this->query->setParameter($field, '%' . $value . '%');
    }

    public function addEntityFilter($field, $value) {
        $metadata = $this->getMetadatas('Wifinder\CallbackBundle\Entity\MailingAddress');
        $targetClass = $metadata->getAssociationTargetClass($field);
        $this->query->innerJoin($targetClass, $field);
        $this->query->andWhere($field . '.id = :' . $field);
        $this->query->andWhere(sprintf('c.%s = :%s', $field, $field));
        $this->query->setParameter($field, $value);
    }
    
    public function addCollectionFilter($field, $value){
        if (strstr($field, '.')) {
            list($table, $field) = explode('.', $field);
        } else {
            $table = $field;
            $field = 'id';
        }
        $this->query->leftJoin('c.'.$table, $table);
        $this->query->andWhere(sprintf('%s.%s IN (:%s)',$table, $field, $table.'_'.$field));
        $this->query->setParameter($table.'_'.$field, $value);
    }

    protected function getMetadatas($class = null) {
        if ($class) {
            self::$current_class = $class;
        }

        if (isset($this->metadata[self::$current_class]) || !$class) {
            return $this->metadata[self::$current_class];
        }

        if (!$this->getDoctrine()->getEntityManager()->getConfiguration()->getMetadataDriverImpl()->isTransient($class)) {
            $this->metadata[self::$current_class] = $this->getDoctrine()->getEntityManager()->getClassMetadata($class);
        }

        return $this->metadata[self::$current_class];
    }

    public function getDbType($class, $fieldName) {
        $metadata = $this->getMetadatas($class);

        if ($metadata->hasAssociation($fieldName)) {
            if ($metadata->isSingleValuedAssociation($fieldName)) {
                return 'entity';
            } else {
                return 'collection';
            }
        }

        if ($metadata->hasField($fieldName)) {
            return $metadata->getTypeOfField($fieldName);
        }

        return 'virtual';
    }

    /**
     * @Template()
     */
    public function newAction(Request $request) {
        $entity = new MailingAddress();

        $form = $this->createForm(new MailingAddressType(), $entity);

        $page = 1;
        if ($request->getSession()->has('admin_mailing_page')) {
            $page = $request->getSession()->get('admin_mailing_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template("CallbackBundle:MailingAddress:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new MailingAddress();
        $form = $this->createForm(new MailingAddressType(), $entity);
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('Entry is successfully saved!')
            );

            if ($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_mailingaddress'));

            return $this->redirect($this->generateUrl('admin_mailingaddress_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('Correct validation errors and try saving again.')
        );

        $page = 1;
        if ($request->getSession()->has('admin_mailing_page')) {
            $page = $request->getSession()->get('admin_mailing_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template()
     */
    public function editAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CallbackBundle:MailingAddress')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_mailingaddress'));
        }

        $editForm = $this->createForm(new MailingAddressType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $page = 1;
        if ($request->getSession()->has('admin_mailing_page')) {
            $page = $request->getSession()->get('admin_mailing_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("CallbackBundle:MailingAddress:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CallbackBundle:MailingAddress')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_mailingaddress'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MailingAddressType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('"%email%" is successfully updated!', array('%email%' => $entity->getEmail()))
            );

            if ($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_mailingaddress'));

            return $this->redirect($this->generateUrl('admin_mailingaddress_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('Correct validation errors and try saving again.')
        );

        $page = 1;
        if ($request->getSession()->has('admin_mailing_page')) {
            $page = $request->getSession()->get('admin_mailing_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CallbackBundle:MailingAddress')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_mailingaddress'));
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('"%email%" is successfully deleted!', array('%email%' => $entity->getEmail()))
            );
        } else {
            $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('Error deleting "%email%".', array('%email%' => $entity->getEmail()))
            );
        }

        return $this->redirect($this->generateUrl('admin_mailingaddress'));
    }

    private function createDeleteForm($id) {
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
            $entity = $em->getRepository('CallbackBundle:MailingAddress')->find($id);

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
        else {
            $active = "";
        }
        return $this->render(
                        'CallbackBundle:MailingAddress:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }

}
