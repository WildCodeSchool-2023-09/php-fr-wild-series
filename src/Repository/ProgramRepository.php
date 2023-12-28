<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Program>
 *
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    public function queryFindAll(): Query
    {
        return $this->createQueryBuilder('p')->orderBy('p.id', 'ASC')->getQuery();
    }

    public function findLikeTitle(string $search, ?Category $category): Query
    {
        $query = $this->createQueryBuilder('p')
            ->join('p.category', 'pc')
            ->andWhere('p.title LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        if ($category) {
              $query
              ->andWhere('p.category = :category')
              ->setParameter('category', $category)  ;
        }
           return $query->getQuery()->getResult();
    }

    public function programCategory(string $category): string
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->andWhere('c.name = :category')
            ->setParameter('category', $category)
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Program[] Returns an array of Program objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Program
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
