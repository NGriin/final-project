<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductSeller;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder('a')
            ->where('a.slug = :slug')
            ->setParameter('slug', $slug)
            ->andWhere('a.is_active != 0')
            ->getQuery()
            ->getSingleResult();
    }

    public function getAllProductsInOrder()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.created_at', 'DESC')
            ->andWhere('a.is_active != 0')
            ->getQuery()
            ->getResult();
    }

    public function findAllByFilters($search, $category, $price, $seller, $features, $sort)
    {
        $qb = $this->createQueryBuilder('a');

        if ($search) {
            $qb
                ->andWhere('a.name LIKE :search')
                ->setParameter('search', "%$search%");
        }

        if($category) {
            $qb
                ->join('a.category', 'c', Join::WITH, 'c.id = :category')
                ->setParameter(':category',$category);
        }

        if($price || $seller) {
            $qb
                ->join('a.productSeller', 'ps');
        }

        if($price) {
            list($lowerPrice,$upperPrice) = explode(';',$price);
            $qb
                ->andWhere('ps.cost_discount > :lowerPrice and ps.cost_discount < :upperPrice')
                ->setParameter('lowerPrice', $lowerPrice)->setParameter('upperPrice', $upperPrice);
        }

        if($seller) {
            $qb
                ->join('ps.seller', 's', Join::WITH, 's.id = :sellerId')
                ->setParameter('sellerId', $seller);
        }

        if(is_array($features) && count($features)) {
            $qb
                ->join('a.features','f',Join::WITH, 'f.id in (:ids)')
                ->setParameter('ids',$features,ArrayParameterType::STRING);
        }


        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findAllWithSearchQuery(?string $search, ?int $category = null, $sort)
    {
        $qb = $this->createQueryBuilder('a');

        if ($search) {
            $qb
                ->andWhere('a.description LIKE :search OR a.name LIKE :search')
                ->setParameter('search', "%$search%");
        }

        if($category) {
            $qb
                ->join('a.category', 'c', Join::WITH, 'c.id = :category')
                ->setParameter(':category',$category);
        }

        switch ($sort) {
            case 'popul': {
                $qb
                    ->orderBy('a.sort_index', 'DESC');
                break;
            }
            case 'otzv': {

            }
            case 'price': {
                $qb
                    ->join('a.productSeller', 'ps')
                    ->orderBy('ps.cost_discount','DESC');
                break;
            }
            default: {
                $qb
                    ->orderBy('a.created_at', 'DESC');
            }
        }

        return $qb
            ->andWhere('a.is_active != 0')
            ->getQuery()
            ->getResult();
    }

    public function findEightTopProducts()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.sort_index', 'DESC')
            ->andWhere('a.is_active != 0')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
