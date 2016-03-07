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

    public function findByDate($date, $identity, $state)
    {
        // var_dump($date->format('Y-m-d') . '%');
        // die('ddd');
        $queryBuilder = $this->createQueryBuilder('contact');
        $queryBuilder
            ->select('contact')
            ->where('contact.manager = :managerId')
            ->andWhere('contact.time LIKE :date')
            ->andWhere('contact.state = :stateId')
            ->setParameter('managerId', $identity->getId())
            ->setParameter('stateId', $state->getId())
            ->setParameter('date', $date->format('Y-m-d') . '%');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findBySibscribedOnMailingLists($databaseIds)
    {
        $queryBuilder = $this->createQueryBuilder('contact');
        $queryBuilder
            ->select('contact.id', 'contact.fullName', 'contact.phone', 'contact.email', 'contact.comment')
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

    public function search($searchBy) 
    {
        $queryBuilder = $this->createQueryBuilder('contact');
        $queryBuilder
            ->select('contact')
            ->where('contact.fullName LIKE :string1')
            ->orWhere('contact.phone LIKE :string2')
            ->orWhere('contact.email LIKE :string3')
            ->setParameter('string1', '%' . $searchBy . '%')
            ->setParameter('string2', '%' . $searchBy . '%')
            ->setParameter('string3', '%' . $searchBy . '%');
        return  $queryBuilder->getQuery()->getResult();

        // $em = $this->getEntityManager();
        // $qb = $em->createQueryBuilder(); 
        // $qb->select('user')
        //     ->from( 'Admin\Entity\User',  'user')
        //     // ->where('user.email LIKE LIKE ?', '%' . $searchBy . '%')
        //     ->where('user.email LIKE :string1')
        //     // ->orWhere('user.fullName LIKE ?', '%' . $searchBy . '%')
        //     ->orWhere('user.fullName LIKE :string2')
        //     ->setParameter('string1', '%' . $searchBy . '%')
        //     ->setParameter('string2', '%' . $searchBy . '%');
        // return  $qb->getQuery()->getResult();
    }
}