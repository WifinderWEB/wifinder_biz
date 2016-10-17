<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectController extends Controller
{
    public function lastProjectOnHomePageAction($limit = 3){
        $em = $this->getDoctrine()->getRepository('ProjectBundle:Project');
        $items = $em->GetLastProjects($limit);

        return $this->render(
             'FrontendContentBundle:Project:_lastProjectOnHomePage.html.twig', array('items' => $items)
        );
    }
    
    public function ShowListProjectsAction(Request $request){
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('frontend_projects_page', $page);
        }
        elseif($request->getSession()->has('frontend_projects_page')){
            $page = $request->getSession()->get('frontend_projects_page');
        }
        else{
            $page = 1;
        }
        
        $year = null;
        if($request->getSession()->has('frontend_project_filter'))
            $year = $request->getSession()->get('frontend_project_filter');
            
        
        $em = $this->getDoctrine()->getRepository('ProjectBundle:Project');
        $query = $em->GetProjects($year);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            10/*limit per page*/
        );
        
        return $this->render(
             'FrontendContentBundle:Project:showListProjects.html.twig',
                array(
                    'pagination' => $pagination,
                    'param' => $pagination->getPaginationData(), 
                    'year' => $year,
                    'page' => $page
                )
        );
    }
    
    public function ShowProjectAction(Request $request,$alias){
        $project = $this->getDoctrine()->getRepository('ProjectBundle:Project')->findOneBy(array('is_active' => true, 'alias' => $alias));
        $year = null;
        if($request->getSession()->has('frontend_project_filter'))
            $year = $request->getSession()->get('frontend_project_filter');
        
        return $this->render(
             'FrontendContentBundle:Project:showProject.html.twig',
                array(
                    'project' => $project,
                    'year' => $year
                )
        );
    }
    
    public function FilterProjectAction(Request $request, $year){
        $request->getSession()->set('frontend_project_filter', $year);
        $request->getSession()->set('frontend_projects_page', 1);
        
        return $this->redirect($this->generateUrl('wifinder_frontend_projects'));
    }
    
    public function ResetFilterProjectAction(Request $request){
        $request->getSession()->remove('frontend_project_filter');
        $request->getSession()->set('frontend_projects_page', 1);
        
        return $this->redirect($this->generateUrl('wifinder_frontend_projects'));
    }
    
     public function LastProjectAction($limit = 3){
        $em = $this->getDoctrine()->getRepository('ProjectBundle:Project');
        $items = $em->GetLastProjects($limit);

        return $this->render(
             'FrontendContentBundle:Project:_lastProject.html.twig', array('items' => $items)
        );
    }
}
