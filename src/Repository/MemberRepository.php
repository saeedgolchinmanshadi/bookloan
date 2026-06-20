<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Member>
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function createSearchQueryBuilder(?string $searchQuery): \Doctrine\ORM\QueryBuilder
    {
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC');

        $searchQuery = trim((string) $searchQuery);
        if ($searchQuery !== '') {
            $qb->andWhere('m.lastName LIKE :search OR m.nationalCode LIKE :search OR m.mobile LIKE :search')
                ->setParameter('search', '%' . $searchQuery . '%');
        }

        return $qb;
    }
}
