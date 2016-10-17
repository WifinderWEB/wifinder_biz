<?php

namespace Wifinder\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class YearsRepository extends EntityRepository
{
    public function getActiveYears(){
        $query = $this->_em->createQueryBuilder()
            ->select("y")
            ->from("ProjectBundle:Years", "y")
            ->innerJoin('ProjectBundle:Project', "p")
            ->where('p.years = y')
            ->orderBy('y.alias', 'desc')
            ->getQuery()->execute();

        return $query;
    }
}
