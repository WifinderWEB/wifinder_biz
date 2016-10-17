<?php

namespace Wifinder\ImageGalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ImageRepository extends EntityRepository
{
    public function GetImagesCategoryId($category_id){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("ImageGalleryBundle:Image", "c")
            ->where("c.category_id = :category_id")
            ->setParameter('category_id', $category_id)
            ->getQuery()
            ->getResult();

        return $query;
    }
    
    public function GetImagesForCategory($alias){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("ImageGalleryBundle:Image", "i")
            ->innerJoin('ImageGalleryBundle:ImageCategory', 'c')
            ->where('c.is_active = true')
            ->andWhere('c.alias = :alias')
            ->setParameter('alias', $alias)
            ->andWhere("i.category = c")
            ->andWhere('i.is_active = true')
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->execute();

        return $query;
    }
}
