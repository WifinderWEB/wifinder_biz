<?php
namespace Wifinder\WebItemBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Wifinder\WebItemBundle\Entity\WebItem;

class WebItemRepository extends EntityRepository
{
    public function GetItemByAlias($alias){
//        $query = $this->_em->createQueryBuilder()
//            ->select("i, t")
//            ->from("WebItemBundle:WebItem", "i")
//            ->leftJoin('i.translations', 't')
//            ->where('i.alias = :alias')
//            ->andWhere('i.is_active = :is_active')
//            ->setParameters(array('alias' => $alias, 'is_active' => true))
//            ->getQuery()->getOneOrNullResult();
//
//        return $query;
         return $this->findOneBy(array('alias' => $alias, 'is_active' => true));
    }
    
    public function retriveWebItem($request){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("WebItemBundle:WebItem", "i")
            ->orderBy('i.id','desc')
            ->getQuery()->execute();

        return $query;
    }
}
