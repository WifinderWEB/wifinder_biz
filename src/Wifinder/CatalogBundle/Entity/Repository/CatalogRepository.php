<?php

namespace Wifinder\CatalogBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CatalogRepository extends NestedTreeRepository
{
    public function retriveCatalog($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CatalogBundle:Catalog", "c")
                ->orderBy('c.root')
                ->addOrderBy('c.lft');

        return $query;
    }
    
    public function GetCategoriesQuery(){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CatalogBundle:Catalog", "c")
                ->where('c.catalog_type = 1')
                ->addOrderBy('c.lft');
                 

        return $query;
    }
    
    public function GetCatalogCategoriesTree($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CatalogBundle:Catalog", "c")
            ->where('c.catalog_type = 1')
            ->andWhere('c.level = 0')
            ->addOrderBy('c.lft')
            ->getQuery()
            ->getOneOrNullResult();

        return $query;
    }
    
    public function GetContentByAlias($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CatalogBundle:Catalog", "c")
            ->where('c.alias = :alias')
            ->andWhere('c.is_active = true')
            ->setParameter('alias', $alias)
            ->getQuery()
            ->getOneOrNullResult();
        
        return $query;
    }
    
    public function GetProductList($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CatalogBundle:Catalog", "c")
            ->innerJoin("CatalogBundle:Catalog", "a")
            ->where("a.alias = :alias")
            ->andWhere('c.lft BETWEEN a.lft AND a.rgt')
            ->andWhere('c.alias != a.alias')
            ->andWhere('c.is_active = true')
            ->andWhere('a.is_active = true')
            ->addOrderBy('c.lft', 'asc')
            ->setParameter('alias', $alias)
            ->getQuery()
            ->getResult();
        
        return $query;
    }
    
    public function GetProduct($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CatalogBundle:Catalog", "c")
            ->where('c.alias = :alias')
            ->andWhere('c.catalog_type = 2')
            ->setParameter('alias', $alias)
            ->getQuery()
            ->getOneOrNullResult();
        
        return $query;
    }
    
    public function getCatalogContainText($text){
        $dql = <<<___SQL
                    SELECT c
                    FROM CatalogBundle:Catalog c
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
                    
        return $query;
    }
    
    public function GetItemsByParentAlias($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("CatalogBundle:Catalog", "c")
            ->innerJoin("CatalogBundle:Catalog", "a")
            ->where("a.alias = :alias")
            ->andWhere('c.lft BETWEEN a.lft AND a.rgt')
            ->andWhere("c.catalog_type = 2")
            ->andWhere('c.is_active = true')
            ->setParameter('alias', $alias)
            ->addOrderBy('c.lft', 'asc')
            ->getQuery()
            ->getResult();
        
        return $query;
    }
}