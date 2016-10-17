<?php

namespace Wifinder\NewsBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class NewsCategoryRepository extends NestedTreeRepository{
    public function retriveNewsCategory($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("NewsBundle:NewsCategory", "c")
            ->getQuery()->execute();

        return $query;
    }
    
    public function GetLastNewsCategory($category, $limit){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("NewsBundle:NewsItem", "i")
            ->innerJoin("NewsBundle:NewsCategory", "c")
            ->where("c.alias = :alias")
            ->andWhere("c.is_active = true")
            ->andWhere('i.category = c')
            ->andWhere('i.publish <= :publish')
            ->andWhere('i.end_date is null OR i.end_date >= :end_date')
            ->andWhere('i.is_active = true')
            ->setParameter('alias', $category)
            ->setParameter('publish', date('Y-m-d 00:00:00'))
            ->setParameter('end_date', date('Y-m-d 00:00:00'))
            ->orderBy('i.publish', 'desc')
            ->addOrderBy('i.id', 'desc')
            ->setMaxResults($limit)
            ->getQuery()->execute();
        return $query;
    }
    
    public function GetLastNews($limit){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("NewsBundle:NewsItem", "i")
            ->innerJoin("NewsBundle:NewsCategory", "c")
            ->andWhere("c.is_active = true")
            ->andWhere('i.category = c')
            ->andWhere('i.publish <= :publish')
            ->andWhere('i.end_date is null OR i.end_date >= :end_date')
            ->andWhere('i.is_active = true')
            ->setParameter('publish', date('Y-m-d 00:00:00'))
            ->setParameter('end_date', date('Y-m-d 00:00:00'))
            ->orderBy('i.publish', 'desc')
            ->addOrderBy('i.id', 'desc')
            ->setMaxResults($limit)
            ->getQuery()->execute();
        return $query;
    }
    
    public function GetListCategories(){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("NewsBundle:NewsCategory", "c")
            ->where("c.is_active = true")
            ->orderBy('c.lft', 'desc')
            ->getQuery()->execute();

        return $query;
    }
}
