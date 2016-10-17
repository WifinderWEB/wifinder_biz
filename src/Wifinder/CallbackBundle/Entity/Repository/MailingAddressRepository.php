<?php

namespace Wifinder\CallbackBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MailingAddressRepository extends EntityRepository{
    public function retriveMailingAddress(){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CallbackBundle:MailingAddress", "c");

        return $query;
    }
    
    public function getEmailListForType($type){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CallbackBundle:MailingAddress", "c")
            ->innerJoin('c.type', 't')
            ->where('c.is_active = true')
            ->andWhere('t.id = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->execute();

        return $query;
    }
}