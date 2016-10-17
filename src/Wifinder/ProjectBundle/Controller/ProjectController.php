<?php

namespace Wifinder\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\ProjectBundle\Entity\Project;
use Wifinder\ProjectBundle\Entity\ProjectImage;
use Wifinder\ProjectBundle\Entity\ProjectFile;
use Wifinder\ProjectBundle\Form\ProjectType;
use Wifinder\ProjectBundle\Form\ProjectFilterType;

/**
 * Project controller.
 *
 */
class ProjectController extends Controller {

    private $query;
    private $metadata;
    private static $current_class;
    
    /**
     * @Template()
     */
    public function indexAction(Request $request) {
        $page = $request->query->get('page');

        if ($page) {
            $request->getSession()->set('admin_project_page', $page);
        } elseif ($request->getSession()->has('admin_project_page')) {
            $page = $request->getSession()->get('admin_project_page');
        } else {
            $page = 1;
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $this->BuildQuery($request), $page, /* page number */ 20/* limit per page */
        );
        $deleteForm = $this->createEmptyDeleteForm();

        $filterForm = $this->createForm(new ProjectFilterType(), $this->getFilterModel($request));

        return array(
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView(),
            'filter_form' => $filterForm->createView()
        );
    }

    public function filterAction(Request $request) {
        $form = $this->createForm(new ProjectFilterType());
        $form->bind($request);

        $this->setFilter($request, $form);
        $request->getSession()->set('admin_project_page', 1);

        return $this->redirect($this->generateUrl('admin_project'));
    }

    public function resetFilterAction(Request $request) {
        $request->getSession()->remove('admin_project_filter');
        $request->getSession()->set('admin_project_page', 1);

        return $this->redirect($this->generateUrl('admin_project'));
    }

    private function BuildQuery($request) {
        $em = $this->getDoctrine()->getManager();
        $this->query = $em->getRepository('ProjectBundle:Project')
                ->retriveProject($request);

        $this->getFilter($request);

        return $this->query->getQuery()->setHint(
                        \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')->execute();
    }

    private function setFilter($request, $form) {
        $filter = array();
        foreach ($form as $one) {
            if ($one->getData() !== null) {
                $type = $this->getDbType('Wifinder\ProjectBundle\Entity\Project', $one->getName());
                if ($type == 'entity')
                    $filter[$one->getName()] = array('type' => $type, 'value' => $one->getData()->getId());
                else
                    $filter[$one->getName()] = array('type' => $type, 'value' => $one->getData());
            }
        }
        $request->getSession()->set('admin_project_filter', $filter);
    }

    private function getFilter($request) {
        if ($request->getSession()->has('admin_project_filter')) {
            foreach ($request->getSession()->get('admin_project_filter') as $i => $one) {
                call_user_func_array(array($this, 'add' . ucfirst($one['type']) . 'Filter'), array($i, $one['value']));
            }
        }
    }

    private function getFilterModel($request) {
        $entity = new Project();
        $entity->setIsActive(null);
        if ($request->getSession()->has('admin_project_filter')) {
            foreach ($request->getSession()->get('admin_project_filter') as $i => $one) {
                if ($one['type'] == 'entity') {
                    $metadata = $this->getMetadatas('Wifinder\ProjectBundle\Entity\Project');
                    $targetClass = $metadata->getAssociationTargetClass($i);
                    $one['value'] = $this->getDoctrine()->getManager()->getRepository($targetClass)->find($one['value']);
                }
                $entity->set($i, $one['value']);
            }
        }
        //exit;
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
        $metadata = $this->getMetadatas('Wifinder\ProjectBundle\Entity\Project');
        $targetClass = $metadata->getAssociationTargetClass($field);

        $this->query->innerJoin($targetClass, $field);
        $this->query->andWhere($field . '.id = :' . $field);
        $this->query->andWhere(sprintf('c.%s = :%s', $field, $field));
        $this->query->setParameter($field, $value);
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
        $entity = new Project();

        $form = $this->createForm(new ProjectType(), $entity);

        $page = 1;
        if ($request->getSession()->has('admin_project_page')) {
            $page = $request->getSession()->get('admin_project_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template("ProjectBundle:Project:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Project();
        $form = $this->createForm(new ProjectType(), $entity);
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('Entry is successfully saved!')
            );

            if ($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_project'));

            return $this->redirect($this->generateUrl('admin_project_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('Correct validation errors and try saving again.')
        );

        $page = 1;
        if ($request->getSession()->has('admin_project_page')) {
            $page = $request->getSession()->get('admin_project_page');
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
        $entity = $em->getRepository('ProjectBundle:Project')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_project'));
        }

        $editForm = $this->createForm(new ProjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $page = 1;
        if ($request->getSession()->has('admin_project_page')) {
            $page = $request->getSession()->get('admin_project_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("ProjectBundle:Project:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ProjectBundle:Project')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_project'));
        }

        $originalImages = array();
        foreach ($entity->getImages() as $image) {
            $originalImages[] = $image;
        }

        $originalFiles = array();
        foreach ($entity->getFiles() as $file) {
            $originalFiles[] = $file;
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ProjectType(), $entity);
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

            foreach ($entity->getFiles() as $file) {
                foreach ($originalFiles as $key => $toDel) {
                    if ($toDel->getId() === $file->getId()) {
                        unset($originalFiles[$key]);
                    }
                }
            }
            foreach ($originalFiles as $file) {
                $em->remove($file);
            }

            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('"%alias%" is successfully updated!', array('%alias%' => $entity->getAlias()))
            );

            if ($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_project'));

            return $this->redirect($this->generateUrl('admin_project_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('Correct validation errors and try saving again.')
        );

        $page = 1;
        if ($request->getSession()->has('admin_project_page')) {
            $page = $request->getSession()->get('admin_project_page');
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
            $entity = $em->getRepository('ProjectBundle:Project')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_project'));
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('"%alias%" is successfully deleted!', array('%alias%' => $entity->getAlias()))
            );
        } else {
            $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('Error deleting "%alias%".', array('%alias%' => $entity->getAlias()))
            );
        }

        return $this->redirect($this->generateUrl('admin_project'));
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
            $entity = $em->getRepository('ProjectBundle:Project')->find($id);

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
                        'ProjectBundle:Project:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }

}
