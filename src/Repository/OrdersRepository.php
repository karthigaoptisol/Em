<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function list(array $filter): ?array
    {
        $q = $this->createQueryBuilder('r');
        if (!empty($filter['order_id'])) {
            $q
                ->andWhere($q->expr()->eq('r.id', ':id'))
                ->setParameter('id', $filter['order_id']);
        }
        if (!empty($filter['status'])) {
            $q
                ->andWhere($q->expr()->eq('r.status', ':status'))
                ->setParameter('status', $filter['status']);
        }
        return $q->getQuery()->getResult();
    }
}
