<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Wifinder\ImageGalleryBundle\Entity\Image;
use Wifinder\ImageGalleryBundle\Form\ImageCategory;

class ImageGalleryController extends Controller
{
    public function indexAction(Request $request, $alias){
        $em = $this->getDoctrine()->getRepository('ImageGalleryBundle:Image');
        $items = $em->GetImagesForCategory($alias);
        
        if(!$items){
            throw $this->createNotFoundException('Unable to find Images in this gallery.');
        }
        return $this->render(
             'FrontendContentBundle:ImageGallery:index.html.twig', 
                 array('items' => $items)
         );
    }
}
