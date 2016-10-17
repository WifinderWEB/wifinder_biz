<?php

namespace Wifinder\PageBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Wifinder\PageBundle\Entity\Content;

class ContentRepository extends EntityRepository
{
     public function GetContentByalias($alias) {
        $entities = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("PageBundle:Content", "i")
//            ->leftJoin('i.meta', 'm')
//            ->leftJoin('m.translations', 'mt')
//            ->leftJoin('i.translations', 't')
            ->where('i.alias = :alias')
            ->andWhere('i.is_active = true')
            ->setParameter('alias', $alias)
            ->getQuery()
            ->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            )
            ->getOneOrNullResult();

        return $entities;
    }
    
    public function retriveContent($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("PageBundle:Content", "c")
            ->getQuery()->execute();

        return $query;
    }
    
    public function getContentContainText($text){
        $dql = <<<___SQL
                    SELECT c
                    FROM PageBundle:Content c
                    INNER JOIN c.meta m
                    WHERE c.is_active = true
                    AND (c.title LIKE '%{$text}%' 
                    OR m.meta_title LIKE '%{$text}%'
                    OR m.meta_keywords LIKE '%{$text}%'
                    OR m.meta_description LIKE '%{$text}%')
                    ORDER BY c.title
___SQL;
        
        $query = $this->_em->createQuery($dql);
        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
                    
        return $query->getResult();
    }
}