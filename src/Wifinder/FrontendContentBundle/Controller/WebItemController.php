<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class WebItemController extends Controller {

    public function renderItemAction($alias) {
        $em = $this->getDoctrine()->getRepository('WebItemBundle:WebItem');
        $item = $em->GetItemByAlias($alias);

        return $this->render(
             'FrontendContentBundle:WebItem:_renderItem.html.twig', array('item' => $item)
        );
    }

}
