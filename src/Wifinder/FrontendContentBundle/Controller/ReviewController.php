<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Wifinder\CallbackBundle\Entity\Review;
use Wifinder\CallbackBundle\Form\FrontendReviewType;

class ReviewController extends Controller
{
    public function ListReviewAction(Request $request){
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('frontend_review_page', $page);
        }
        elseif($request->getSession()->has('frontend_review_page')){
            $page = $request->getSession()->get('frontend_review_page');
        }
        else{
            $page = 1;
        }
        
        $em = $this->getDoctrine()->getRepository('CallbackBundle:Review');
        $query = $em->GetReviews();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            20/*limit per page*/
        );
        
        return $this->render(
             'FrontendContentBundle:Review:showListReview.html.twig',
                array(
                    'pagination' => $pagination,
                    'param' => $pagination->getPaginationData()
                )
        );
    }
    
     public function NewReviewAction(){
         $entity = new Review();
         $form   = $this->createForm(new FrontendReviewType(), $entity);
         
         return $this->render(
              'FrontendContentBundle:Review:_newReview.html.twig', array(
              'form' => $form->createView()
         ));
     }
     
     public function CreateReviewAction(Request $request){
         $entity = new Review();
         $form = $this->createForm(new FrontendReviewType(), $entity);
         $form->bind($request);

         if ($form->isValid()) {
             $em = $this->getDoctrine()->getManager();
             $entity->setIsActive(true);
             $em->persist($entity);
             $em->flush();
             
             return $this->render(
                  'FrontendContentBundle:Review:_reviewSaved.html.twig', array()
             );
         }
         
         return $this->render(
             'FrontendContentBundle:Review:_reviewForm.html.twig', array(
             'form' => $form->createView()
         ));
     }
     
     public function lastReviewOnHomePageAction($limit){
         $em = $this->getDoctrine()->getRepository('CallbackBundle:Review');
         $items = $em->GetLastReview($limit);

         return $this->render(
             'FrontendContentBundle:Review:_lastReviewOnHomePage.html.twig', array('items' => $items)
         );
     }
     
     public function LastReviewAction($limit = 3){
         $em = $this->getDoctrine()->getRepository('CallbackBundle:Review');
         $items = $em->GetLastReview($limit);

         return $this->render(
             'FrontendContentBundle:Review:_lastReview.html.twig', array('items' => $items)
         );
     }
}
