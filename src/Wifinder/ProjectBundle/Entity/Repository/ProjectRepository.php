<?php

namespace Wifinder\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Wifinder\ProjectBundle\Entity\Project;
use Wifinder\ProjectBundle\Entity\Years;

class ProjectRepository extends EntityRepository
{
    public function retriveProject($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("ProjectBundle:Project", "c");

        return $query;
    }
    
    public function GetLastProjects($limit){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("ProjectBundle:Project", "c")
            ->where("c.is_active = true")
            ->orderBy("c.sort", "desc")
            ->addOrderBy("c.id", "desc")
            ->setMaxResults($limit)
            ->getQuery()->execute();

        return $query;
    }
    
    public function GetProjects($year){
        $query = $this->_em->createQueryBuilder()
            ->addSelect('c as project, y.alias as year')
            ->from("ProjectBundle:Project", "c")
            ->innerJoin('ProjectBundle:Years', 'y')
            ->where('c.years = y')
            ->andWhere("c.is_active = true")
            ->addOrderBy("year", "desc")
            ->addOrderBy("c.id", "desc");
        if($year){
           $query = $query
                   ->andWhere('c.years = y')
                   ->andWhere('y.alias = :year')
                   ->setParameter('year', $year);
                
        }
        
        $query = $query->getQuery()->execute();
        return $query;
    }
}
