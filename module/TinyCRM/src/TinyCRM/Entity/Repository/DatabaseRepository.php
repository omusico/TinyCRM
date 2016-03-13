<?php

namespace TinyCRM\Entity\Repository;

use Doctrine\ORM\EntityRepository; 

class DatabaseRepository extends \VisoftMailerModule\Entity\Repository\DatabaseRepository
{
    public function findAvailable($identity)
    {
        // var_dump($date->format('Y-m-d') . '%');
        // die('ddd');
        $queryBuilder = $this->createQueryBuilder('database');
        $queryBuilder
            ->select('database')
            ->leftJoin('database.managers', 'managers')
            ->where($queryBuilder->expr()->orX(
            	$queryBuilder->expr()->eq('database.individual', 0),
            	$queryBuilder->expr()->in('managers',$identity->getId())
            ));
        return $queryBuilder->getQuery()->getResult();
    }
}