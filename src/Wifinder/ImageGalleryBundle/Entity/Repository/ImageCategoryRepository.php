<?php

namespace Wifinder\ImageGalleryBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ImageCategoryRepository extends EntityRepository
{
    public function retriveImageCategory($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("ImageGalleryBundle:ImageCategory", "c")
            ->addOrderBy('c.id');

        return $query;
    }
}
