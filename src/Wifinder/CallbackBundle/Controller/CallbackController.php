<?php

namespace Wifinder\CallbackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Wifinder\CallbackBundle\Entity\Callback;
use Wifinder\CallbackBundle\Form\CallbackType;
use Wifinder\CallbackBundle\Form\CallbackFilterType;

/**
* @Template()
*/
class CallbackController extends Controller
{
    private $query;
    private $metadata;
    private static $current_class;
    
    public function indexAction(Request $request) {
        $page = $request->query->get('page');

        if ($page) {
            $request->getSession()->set('admin_callback_page', $page);
        } elseif ($request->getSession()->has('admin_callback_page')) {
            $page = $request->getSession()->get('admin_callback_page');
        } else {
            $page = 1;
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $this->BuildQuery($request), $page, /* page number */ 20/* limit per page */
        );

        $filterForm = $this->createForm(new CallbackFilterType(), $this->getFilterModel($request));

        return array(
            'pagination' => $pagination,
            'filter_form' => $filterForm->createView()
        );
    }

    public function filterAction(Request $request) {
        $form = $this->createForm(new CallbackFilterType());
        $form->bind($request);

        $this->setFilter($request, $form);
        $request->getSession()->set('admin_callback_page', 1);

        return $this->redirect($this->generateUrl('admin_callback'));
    }

    public function resetFilterAction(Request $request) {
        $request->getSession()->remove('admin_callback_filter');
        $request->getSession()->set('admin_callback_page', 1);

        return $this->redirect($this->generateUrl('admin_callback'));
    }

    private function BuildQuery($request) {
        $em = $this->getDoctrine()->getManager();
        $this->query = $em->getRepository('CallbackBundle:Callback')
                ->retriveCallback($request);

        $this->getFilter($request);

        return $this->query->getQuery()->setHint(
                        \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')->execute();
    }

    private function setFilter($request, $form) {
        $filter = array();
        foreach ($form as $one) {
            if ($one->getData() !== null) {
                $type = $this->getDbType('Wifinder\CallbackBundle\Entity\Callback', $one->getName());
                if ($type == 'entity')
                    $filter[$one->getName()] = array('type' => $type, 'value' => $one->getData()->getId());
                else
                    $filter[$one->getName()] = array('type' => $type, 'value' => $one->getData());
            }
        }
        $request->getSession()->set('admin_callback_filter', $filter);
    }

    private function getFilter($request) {
        if ($request->getSession()->has('admin_callback_filter')) {
            foreach ($request->getSession()->get('admin_callback_filter') as $i => $one) {
                call_user_func_array(array($this, 'add' . ucfirst($one['type']) . 'Filter'), array($i, $one['value']));
            }
        }
    }

    private function getFilterModel($request) {
        $entity = new Callback();
        $entity->setIsReceived(null);
        if ($request->getSession()->has('admin_callback_filter')) {
            foreach ($request->getSession()->get('admin_callback_filter') as $i => $one) {
                if ($one['type'] == 'entity') {
                    $metadata = $this->getMetadatas('Wifinder\CallbackBundle\Entity\Callback');
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
        $metadata = $this->getMetadatas('Wifinder\CallbackBundle\Entity\Callback');
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
    
    public function changeActiveAction(Request $request) {
        if ($request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $active = $request->request->get('active');
            
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CallbackBundle:Callback')->find($id);

            if (!$entity) {
                return null;
            }

            if ($active)
                $active = 0;
            else
                $active = 1;

            $entity->setIsReceived($active);
            $em->persist($entity);
            $em->flush();
        }
        else{
            $active = "";
        }
        return $this->render(
                        'CallbackBundle:Callback:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }
    
    public function getFileWithOriginNameAction($id) {
        $entity = $this->getDoctrine()->getRepository('CallbackBundle:Callback')->find($id);
        if (file_exists($entity->getAbsolutePath())) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $entity->getOriginFileName());
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($entity->getAbsolutePath()));
            // читаем файл и отправляем его пользователю
            if ($fd = fopen($entity->getAbsolutePath(), 'rb')) {
                while (!feof($fd)) {
                    print fread($fd, 1024);
                }
                fclose($fd);
            }
            exit;
        }
    }
}
