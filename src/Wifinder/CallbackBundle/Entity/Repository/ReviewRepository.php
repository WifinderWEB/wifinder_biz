<?php

namespace Wifinder\CallbackBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Wifinder\CallbackBundle\Entity\Review;

class ReviewRepository extends EntityRepository{
    public function retriveReview(){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CallbackBundle:Review", "c");

        return $query;
    }
    
    public function GetReviews(){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CallbackBundle:Review", "c")
            ->where("c.is_active = true")
            ->orderBy("c.id", "desc")
            ->getQuery()->execute();

        return $query;
    }
    
    public function GetLastReview($limit){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CallbackBundle:Review", "c")
            ->where("c.is_active = true")
            ->orderBy("c.id", "desc")
            ->setMaxResults($limit)
            ->getQuery()->execute();

        return $query;
    }
}