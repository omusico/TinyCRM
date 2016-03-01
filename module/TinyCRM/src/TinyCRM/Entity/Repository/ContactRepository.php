<?php

namespace TinyCRM\Entity\Repository;

use Doctrine\ORM\EntityRepository; 

class ContactRepository extends \VisoftMailerModule\Entity\Repository\ContactRepository
{
    // public function getDataForCalendar($identity)
    // {
    //     // $state = 
    //     // var_dump($state);
    //     // die('ddd');
    //     $queryBuilder = $this->createQueryBuilder('contact');
    //     $queryBuilder
    //         ->select('MONTH(contact.time) AS month', 'DAY(contact.time) AS day')
    //         ->where('contact.manager = :managerId')
    //         ->andWhere('contact.state = :state')
    //         ->setParameter('managerId', $identity->getId())
    //         ->setParameter('state', $state->getId())
    //         ->groupBy('month')
    //         ->addGroupBy('day');
    //     return $queryBuilder->getQuery()->getResult();
    // }

    public function findByDate($date, $identity)
    {
        // var_dump($date->format('Y-m-d') . '%');
        // die('ddd');
        $queryBuilder = $this->createQueryBuilder('contact');
        $queryBuilder
            ->select('contact')
            ->where('contact.manager = :managerId')
            ->andWhere('contact.time LIKE :date')
            ->setParameter('managerId', $identity->getId())
            ->setParameter('date', $date->format('Y-m-d') . '%');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findBySibscribedOnMailingLists($databaseIds)
    {
        $queryBuilder = $this->createQueryBuilder('contact');
        $queryBuilder
            ->select('contact.id', 'contact.fullName', 'contact.phone', 'contact.email')
            ->leftJoin('contact.databases', 'databases')
            ->add('where', $queryBuilder->expr()->in('databases', $databaseIds));
        return $queryBuilder->getQuery()->getResult();
    }

    public function getCountByDatabaseIds($databaseIds)
    {
        $queryBuilder = $this->createQueryBuilder('contact');
        $queryBuilder
            ->select('count(contact.id)')
            ->leftJoin('contact.databases', 'databases')
            ->add('where', $queryBuilder->expr()->in('databases', $databaseIds));
        return  $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function findByUnibscribedFromMailingLists($databaseIds)
    {
        return null;
    }
}