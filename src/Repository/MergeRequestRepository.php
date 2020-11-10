<?php

namespace App\Repository;

use App\Entity\MergeRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MergeRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method MergeRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method MergeRequest[]    findAll()
 * @method MergeRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MergeRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MergeRequest::class);
    }

    public function saveCollection(array $mergeRequests): array
    {
        foreach ($mergeRequests as $mergeRequest) {
            $this->_em->persist($mergeRequest);
        }
        $this->_em->flush();

        return $mergeRequests;
    }

    public function removeCollection(array $mergeRequests): array
    {
        $ids = [];
        foreach ($mergeRequests as $mergeRequest) {
            $ids[] = $mergeRequest->getGitlabId();
            $this->_em->remove($mergeRequest);
        }
        $this->_em->flush();

        return $ids;
    }

    // /**
    //  * @return MergeRequest[] Returns an array of MergeRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MergeRequest
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
