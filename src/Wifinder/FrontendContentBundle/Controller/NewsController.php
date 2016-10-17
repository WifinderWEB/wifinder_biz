<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsController extends Controller
{
    public function lastNewsOnHomePageAction($category, $limit = 3){
        $em = $this->getDoctrine()->getRepository('NewsBundle:NewsCategory');
        $items = $em->GetLastNews($limit);

        return $this->render(
             'FrontendContentBundle:News:_lastNewsOnHomePage.html.twig', array('items' => $items)
        );
    }
    
    public function lastNewsAction($category, $limit = 5){
        $em = $this->getDoctrine()->getRepository('NewsBundle:NewsCategory');
        $items = $em->GetLastNews($limit);

        return $this->render(
             'FrontendContentBundle:News:_lastNews.html.twig', array('items' => $items)
        );
    }
    
    public function LastNewsCategoryAction($category, $limit = 5){
        $em = $this->getDoctrine()->getRepository('NewsBundle:NewsCategory');
        $items = $em->GetLastNewsCategory($category, $limit);

        return $this->render(
             'FrontendContentBundle:News:_lastNewsCategory.html.twig', array('items' => $items)
        );
    }
    
    public function ShowListCategoriesAction(){
        $em = $this->getDoctrine()->getRepository('NewsBundle:NewsCategory');
        $categories = $em->GetListCategories();
        
        if(count($categories) == 1){
             return $this->redirect($this->generateUrl('wifinder_frontend_news_category', 
                    array(
                        'category' => $categories[0]->getAlias())));
        }
             
        return $this->render(
             'FrontendContentBundle:News:listCategories.html.twig', array('categories' => $categories)
        );
    }
    
    public function ShowCategoryAction(Request $request, $category){
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('frontend_news_category_page', $page);
        }
        elseif($request->getSession()->has('frontend_news_category_page')){
            $page = $request->getSession()->get('frontend_news_category_page');
        }
        else{
            $page = 1;
        }
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('NewsBundle:NewsItem')
                ->GetNewsCategory($category);
        
        $categoryContent = $query[0]->GetCategory();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            20/*limit per page*/
        );

        return $this->render(
             'FrontendContentBundle:News:showCategory.html.twig', array(
                 'category' => $categoryContent,
                 'pagination' => $pagination,
                 'param' => $pagination->getPaginationData()
                )
        );
    }
    
    public function ShowNewsAction($category, $alias){
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('NewsBundle:NewsItem')->GetNewsItem($alias);
        
        return $this->render(
             'FrontendContentBundle:News:showNews.html.twig', array('news' => $result)
        );
    }
}
