<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContentController extends Controller
{
    public function showAction(Request $request, $alias)
    {
        $this->getRequest()->getSession()->set('frontent_content_search', null);
                
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $content = $em->GetContentByalias($alias);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content with alias = ['.$alias.']');
        }

        if($alias == 'homepage'){
            return $this->render(
                'FrontendContentBundle:Content:homepage.html.twig', array(
                    'content' => $content)
            );
        }
        return $this->render(
            'FrontendContentBundle:Content:show.html.twig', array(
                'content' => $content)
        );
    }
    
    public function searchAction(){
        $defaultData = array('text' => $this->getRequest()->getSession()->get('frontent_content_search'));
        $form = $this->createSearchForm($defaultData);
     
        return $this->render(
                    'FrontendContentBundle:Content:_search.html.twig', array(
                        'form' => $form->createView())
        );
    }
    
    public function seacheResultAction(Request $request){
        $data = $this->getRequest()->getSession()->get('frontent_content_search');
        $defaultData = array('text' => $data);
        $form = $this->createSearchForm($defaultData);
 
        $result = array();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            $data = $form['text']->getData();
            
            $request->getSession()->set('frontent_content_search', $data);
        }
        
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $result = $em->getContentContainText($data);
        
        return $this->render(
            'FrontendContentBundle:Content:resultSearche.html.twig', array(
                'query'  => $data,
                'result' => $result)
        );
    }
    
    private function createSearchForm($defaultData) {
        return $this->createFormBuilder($defaultData)
            ->add('text', 'text', array(
                'required' => true,
                'max_length' => 255,
                'attr' => array(
                    'id' => 'prependedInput' 
                )
            ))
            ->getForm()
        ;
    }
    
    public function orderAction(Request $request, $alias)
    {
        $this->getRequest()->getSession()->set('frontent_content_search', null);
                
        $em = $this->getDoctrine()->getRepository('CatalogBundle:Catalog');
        $content = $em->GetProduct($alias);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content with alias = ['.$alias.']');
        }
        
        $em = $this->getDoctrine()->getRepository('PageBundle:Content');
        $order = $em->GetContentByalias('order');

        return $this->render(
            'FrontendContentBundle:Content:order.html.twig', array(
                'content' => $content,
                'order' => $order)
        );
    }
}

