<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CatalogController extends Controller {

    public function indexAction(Request $request, $alias) {
        
        $this->getRequest()->getSession()->set('frontent_content_search', null);
        
        $em = $this->getDoctrine()->getRepository('CatalogBundle:Catalog');
        $content = $em->GetContentByAlias($alias);

        if ($content->getCatalogType()->getId() == 2)
            return $this->redirect($this->generateUrl('wifinder_frontend_catalog_show', 
                    array(
                        'alias' => $content->getAlias(),
                        'category' => $content->getParent()->getAlias())));

        return $this->render(
                        'FrontendContentBundle:Catalog:index.html.twig', array(
                    'content' => $content,
                    'alias' => $alias
                        )
        );
    }

    public function showAction(Request $request, $category, $alias) {
        
        $this->getRequest()->getSession()->set('frontent_content_search', null);
        
        $em = $this->getDoctrine()->getRepository('CatalogBundle:Catalog');
        $content = $em->GetProduct($alias);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content with alias = [' . $alias . ']');
        }

        return $this->render(
                        'FrontendContentBundle:Catalog:show.html.twig', array(
                    'content' => $content,
                    'category' => $category
                        )
        );
    }

    public function productListAction($alias) {
        $em = $this->getDoctrine()->getRepository('CatalogBundle:Catalog');
        $content = $em->GetProductList($alias);

        return $this->render(
                        'FrontendContentBundle:Catalog:_productList.html.twig', array(
                    'content' => $content,
                    'alias' => $alias
                        )
        );
    }

    public function filesListAction($files) {
        $lit = array();
        $cert = array();
        $tone = array();
        $other = array();
        foreach ($files as $one) {
            if($one->getFileCategory()){
                $categoryId = $one->getFileCategory()->getId();
                if ($categoryId == 1)
                    $tone[] = $one;
                elseif($categoryId == 2)
                    $lit[] = $one;
                else
                    $cert[] = $one;
            }
            else{
                $other[] = $one;
            }
        }

        return $this->render(
                        'FrontendContentBundle:Catalog:_filesList.html.twig', array(
                    'lit' => $lit,
                    'cert' => $cert,
                    'tone' => $tone,
                    'other' => $other
                        )
        );
    }
    
    public function seacheResultAction(Request $request, $page = 1){
        $data = $this->getRequest()->getSession()->get('frontent_content_search');
        $defaultData = array('text' => $data);
        $form = $this->createSearchForm($defaultData);
 
        $result = array();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            $data = $form['text']->getData();
            
            $request->getSession()->set('frontent_content_search', $data);
        }

        $em = $this->getDoctrine()->getRepository('CatalogBundle:Catalog');
        $query = $em->getCatalogContainText($data);

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
                $query, $page/* page number */, 10/* limit per page */
        );
        
        return $this->render(
            'FrontendContentBundle:Catalog:resultSearche.html.twig', array(
                'query'  => $data,
                'param' => $result->getPaginationData(),
                'result' => $result)
        );
    }
    
    private function createSearchForm($defaultData) {
        return $this->createFormBuilder($defaultData)
            ->add('text', 'text', array(
                'required' => true,
                'max_length' => 255,
                'attr' => array(
                    'placeholder' => 'Search',
                    'class' => 'search-query' 
                )
            ))
            ->getForm()
        ;
    }
    
    public function caruselBrandsAction($alias){
        $em = $this->getDoctrine()->getRepository('CatalogBundle:Catalog');
        $content = $em->GetItemsByParentAlias($alias);
        
        $root = $content[0]->getParent();
        
        return $this->render(
            'FrontendContentBundle:Catalog:_caruselBrandsOnhomePage.html.twig', array(
                'root'  => $root,
                'content' => $content)
        );
    }
}
