<?php

namespace Wifinder\MenuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wifinder\MenuBundle\Entity\Menu;
use Wifinder\MenuBundle\Entity\MenuItem;
use Wifinder\MenuBundle\Form\MenuType;
use Wifinder\MenuBundle\Entity\Repository\MenuItemRepository;

class MenuController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MenuBundle:Menu')->findAll();

        $deleteForm = $this->createEmptyDeleteForm();
        
        return array(
            'entities' => $entities,
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * @Template()
     */
    public function newAction()
    {
        $entity = new Menu();
        $form   = $this->createForm(new MenuType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template("MenuBundle:Menu:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Menu();
        $form = $this->createForm(new MenuType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $itemRoot = new MenuItem();
            $itemRoot->setAlias($entity->getAlias());
            $itemRoot->setTitle($entity->getName());
            $itemRoot->setLink('#');
            $itemRoot->setMenu($entity);
            $itemRoot->setIsVisible(false);
            $itemRoot->setParentId(0);
            
            $em->persist($itemRoot);
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Menu "%name%" is successfully saved!', array('%name%' => $entity->getName()))
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_menu'));
            
            return $this->redirect($this->generateUrl('admin_menu_edit', array('id' => $entity->getId())));
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
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MenuBundle:Menu')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }

        $editForm = $this->createForm(new MenuType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("MenuBundle:Menu:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MenuBundle:Menu')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_menu'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MenuType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Menu "%name%" is successfully updated!', array('%name%' => $entity->getName()))
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_menu'));
            
            return $this->redirect($this->generateUrl('admin_menu_edit', array('id' => $entity->getId())));
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

    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MenuBundle:Menu')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('warnin',
                    $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_menu'));
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Menu "%name%" is successfully deleted!', array('%name%' => $entity->getName()))
            );
        }
        else{
            $this->get('session')->getFlashBag()->add('error',
                $this->get('translator')->trans('Error deleting "%name%".', array('%name%' => $entity->getName()))
            );
        }

        return $this->redirect($this->generateUrl('admin_menu'));
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
            $entity = $em->getRepository('MenuBundle:Menu')->find($id);

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
                        'MenuBundle:Menu:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }
    
    public function mainMenuAction($alias = 'main'){
        $items = array(
            array('id' => 1, 'title' => 'Каталог', 'link' => 'admin_catalog'),
            array('id' => 2, 'title' => 'Контент', 'link' => 'admin_content'),
            array('id' => 3, 'title' => 'Новости', 'link' => 'admin_news_category'),
            array('id' => 4, 'title' => 'Проекты', 'link' => 'admin_project'),
            array('id' => 5, 'title' => 'Элементы', 'link' => 'admin_web_item'),
            array('id' => 6, 'title' => 'Галереи изображений', 'link' => 'admin_image_gallery_category'),
            array('id' => 8, 'title' => 'Формы', 'child' => array(
                array('id' => 9, 'title' => 'Отзывы', 'link' => 'admin_review'),
                array('id' => 10, 'title' => 'Заявки на обратную связь', 'link' => 'admin_callback'),
                array('id' => 11, 'title' => 'Адреса рассылок', 'link' => 'admin_mailingaddress')
            )),
            array('id' => 7, 'title' => 'Меню', 'link' => 'admin_menu')
        );

        return $this->render(
            'MenuBundle:Menu:_main.html.twig',
            array('items' => $items)
        );
    } 
}
