<?php

namespace Wifinder\LayoutBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class LayoutRepository extends EntityRepository
{
     public function retriveLayout($request){
         $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("LayoutBundle:Layout", "c")
            ->getQuery()->execute();

        return $query;
     }
}