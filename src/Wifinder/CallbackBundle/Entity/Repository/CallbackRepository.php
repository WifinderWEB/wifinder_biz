<?php

namespace Wifinder\CallbackBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CallbackRepository extends EntityRepository{
    public function retriveCallback(){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CallbackBundle:Callback", "c")
            ->orderBy("c.id", "desc");

        return $query;
    }
}
