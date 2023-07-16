<?php

namespace App\Repository;

use App\Entity\ProductSeller;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductSeller>
 *
 * @method ProductSeller|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSeller|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSeller[]    findAll()
 * @method ProductSeller[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSellerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductSeller::class);
    }

    public function getByProductSlug(string $slug)
    {
        return
            $this->createQueryBuilder('ps')
                ->join('ps.product', 'p', Join::WITH, 'p.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()->getResult();
    }

    public function getAVGDiscountByProductSlug(string $slug)
    {
        return
        $this->createQueryBuilder('ps')
            ->select('AVG(CASE WHEN ps.cost_discount IS NOT NULL THEN ps.cost_discount ELSE ps.cost END) as average_cost')
            ->join('ps.product', 'p')
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()->getSingleScalarResult();
    }

    public function getAVGByProductSlug(string $slug)
    {
        return
            $this->createQueryBuilder('ps')
                ->select('AVG(ps.cost) as average_cost')
                ->join('ps.product', 'p')
                ->where('p.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()->getSingleScalarResult();
    }


    public function save(ProductSeller $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductSeller $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ProductSeller[] Returns an array of ProductSeller objects
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

//    public function findOneBySomeField($value): ?ProductSeller
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
