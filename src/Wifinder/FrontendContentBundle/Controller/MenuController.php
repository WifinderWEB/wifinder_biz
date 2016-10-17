<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MenuController extends Controller {

    public function siteMapAction(){
        $this->getRequest()->getSession()->set('frontent_content_search', null);
        
        $em = $this->getDoctrine()->getRepository('MenuBundle:Menu');
        $menu = $em->findOneBy(array('alias' => 'main'));
        
        $em = $this->getDoctrine()->getRepository('MenuBundle:MenuItem');
        $items = $em->GetTreeForMenu($menu->getId());
        
        $em = $this->getDoctrine()->getRepository('CatalogBundle:Catalog');
        $catalog = $em->GetCategoriesQuery()->getQuery()->getResult();
        
        return $this->render(
               'FrontendContentBundle:Menu:siteMap.html.twig', array(
                    'items' => $items,
                    'catalog' => $catalog)
        );
    }
    
    public function topMenuAction($alias) {
        $em = $this->getDoctrine()->getRepository('MenuBundle:MenuItem');
        $items = $em->GetItemsTopLevelForParentAlias($alias);

        return $this->render(
                        'FrontendContentBundle:Menu:_topMenu.html.twig', array('items' => $items)
        );
    }

    public function sidebarMenuAction($alias) {
        $em = $this->getDoctrine()->getRepository('MenuBundle:MenuItem');
        $items = $em->GetItemsTopLevelForParentAlias($alias);
        if(!$items)
            $items = $em->GetItemsLevelForAlias($alias);
        return $this->render(
                        'FrontendContentBundle:Menu:_sidebarMenu.html.twig', array(
                    'items' => $items,
                    'alias' => $alias)
        );
    }

    public function breadCrumbAction($alias) {
        $em = $this->getDoctrine()->getRepository('MenuBundle:MenuItem');
        $crumbs = $em->GetPathForAlias($alias);
        
        return $this->render(
                        'FrontendContentBundle:Menu:_breadCrumb.html.twig', array(
                    'items' => $crumbs,
                    'alias' => $alias)
        );
    }
    
    public function bottomMenuAction($alias) {
        $em = $this->getDoctrine()->getRepository('MenuBundle:MenuItem');
        $items = $em->GetItemsTopLevelForParentAlias($alias);

        return $this->render(
                        'FrontendContentBundle:Menu:_bottomMenu.html.twig', array('items' => $items)
        );
    }
    
    public function sidebarCatalogMenuAction($alias) {
        $em = $this->getDoctrine()->getRepository('CatalogBundle:Catalog');
        $items = $em->GetCatalogCategoriesTree($alias);
        
        return $this->render(
                        'FrontendContentBundle:Menu:_sidebarCatalogMenu.html.twig', array(
                    'catalog' => $items,
                    'active' => $alias)
        );
    }
    
    public function languageSwitcherAction($request){
        $routName = $request->get('_route');
        $active = $request->get('_locale'); 
        $routeParams = $request->get('_route_params');
        unset($routeParams['_locale']);
        
        $langs = array('ru' => 'рус', 'en' => 'engl');

        return $this->render(
                        'FrontendContentBundle:Menu:_languageSwitcher.html.twig', array(
                    'langs'      => $langs,
                    'activeLang' => $active,
                    'routname'   => $routName,
                    'params'     => $routeParams)
        );
    }
    
    public function sidebarYearsMenuAction(Request $request){
        $em = $this->getDoctrine()->getRepository('ProjectBundle:Years');
        $items = $em->getActiveYears();
        $active = null;
        if($request->getSession()->has('frontend_project_filter'))
            $active = $request->getSession()->get('frontend_project_filter');
        
        return $this->render(
                    'FrontendContentBundle:Menu:_sidebarYearsMenu.html.twig', array(
                    'items' => $items,
                    'active' => $active
                ));
    }
}

