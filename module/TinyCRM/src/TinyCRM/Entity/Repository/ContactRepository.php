<?php

namespace TinyCRM\Entity\Repository;

use Doctrine\ORM\EntityRepository; 

class ContactRepository extends \VisoftMailerModule\Entity\Repository\ContactRepository
{
    public function findByDate($date, $identity, $state)
    {
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
    }

    public function getContactsForManager($databaseId, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('contact');
        $queryBuilder
            ->select('contact')
            ->leftJoin('contact.databases', 'databases')
            ->where('databases = :databaseId')
            ->andWhere('contact.manager is NULL')
            ->setParameter('databaseId', $databaseId)
            ->setMaxResults($limit);
        return  $queryBuilder->getQuery()->getResult();
    }
}