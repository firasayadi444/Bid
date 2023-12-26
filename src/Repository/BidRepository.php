<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Bid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bid>
 *
 * @method Bid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bid[]    findAll()
 * @method Bid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bid::class);
    }

    public function findBidsForUserAndArticle($articleId)
    {
        return $this->createQueryBuilder('b')
            ->join('b.article', 'a')
            ->where('a.id = :articleId')
            ->setParameters([
                'articleId' => $articleId,
            ])
            ->getQuery()
            ->getResult();
    }
    // $winningbidingprice
    public function getMaxBidAmountForArticle(Article $article): ?float
    {
        return $this->createQueryBuilder('b')
            ->select('MAX(b.bidingprice) as maxBidAmount')
            ->where('b.article = :article')
            ->setParameter('article', $article)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countBidsForArticle(int $articleId): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.article = :articleId')
            ->setParameter('articleId', $articleId)
            ->getQuery()
            ->getSingleScalarResult();
    }


//    /**
//     * @return Bid[] Returns an array of Bid objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bid
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
