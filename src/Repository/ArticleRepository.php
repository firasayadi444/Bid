<?php

namespace App\Repository;

use App\Entity\Article;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
        $this->date_deb = new DateTimeImmutable('now');

    }
    /**
     * Get articles by user ID
     *
     * @param int $userId
     * @return Article[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')  // Assuming 'user' is the property in the Article entity representing the user
            ->setParameter('user', $userId)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    /**
     * Get articles excluding those owned by a specific user
     *
     * @param int $userId
     * @return Article[]
     */
    public function findArticlesNotOwnedByUser(int $userId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user != :user')  // Assuming 'user' is the property in the Article entity representing the user
            ->setParameter('user', $userId)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
