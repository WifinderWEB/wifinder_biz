<?php

namespace Wifinder\NewsBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class NewsItemRepository extends EntityRepository{
    public function retriveNewsItem($category_id){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("NewsBundle:NewsItem", "i")
            ->innerJoin("NewsBundle:NewsCategory", "c")
            ->where("c.id = :category_id")
            ->andWhere('i.category = c')
            ->setParameter('category_id', $category_id)
            ->getQuery()->execute();
        return $query;
    }
    
    public function GetNewsCategory($category){
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
            ->getQuery()->execute();

        return $query;
    }

    public function GetNewsItem($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("NewsBundle:NewsItem", "i")
            ->where('i.alias = :alias')
            ->andWhere('i.is_active = true')
            ->andWhere('i.publish <= :publish')
            ->andWhere('i.end_date is null OR i.end_date >= :end_date')
            ->setParameter('alias', $alias)
            ->setParameter('publish', date('Y-m-d 00:00:00'))
            ->setParameter('end_date', date('Y-m-d 00:00:00'))
            ->orderBy('i.publish', 'desc')
            ->orderBy('i.id', 'desc')
            ->getQuery()->getOneOrNullResult();
        
        return $query;
    }
    
    public function GetNewsNotSent(){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("NewsBundle:NewsItem", "i")
            ->where('i.is_active = true')
            ->andWhere('i.is_sent = false')
            ->andWhere('i.publish <= :publish')
            ->andWhere('i.end_date is null OR i.end_date >= :end_date')
            ->setParameter('publish', date('Y-m-d 00:00:00'))
            ->setParameter('end_date', date('Y-m-d 00:00:00'))
            ->orderBy('i.publish', 'asc')
            ->orderBy('i.id', 'asc');
            $query = $query->getQuery();
//            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
$query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, 'ru');

            $query = $query->getResult();
        return $query;
    } 
}
