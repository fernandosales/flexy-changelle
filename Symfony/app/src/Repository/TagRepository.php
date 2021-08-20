<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Join;

class TagRepository extends EntityRepository
{
    /**
     * @return Tag[]
     */
    public function find10TagsMostUseds()
    {
        $qb = $this->createQueryBuilder('t');
        return $qb->select('t.name, count(p.id) as qtd')
            ->leftJoin('t.products', 'p')
            ->andWhere('p.id IS NOT NULL ')
            ->groupBy('t.name')
            ->orderBy('qtd', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }

}