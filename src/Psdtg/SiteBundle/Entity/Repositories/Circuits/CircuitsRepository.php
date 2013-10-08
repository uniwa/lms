<?php

namespace Psdtg\SiteBundle\Entity\Repositories\Circuits;

use Psdtg\SiteBundle\Entity\Repositories\BaseRepository;

use Doctrine\ORM\QueryBuilder;

class CircuitsRepository extends BaseRepository
{
    public function findCircuits($filters = array()) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c');
        $qb->from($this->_entityName, 'c');

        $this->addFilters($qb, $filters);

        return $qb->getQuery()->getResult();
    }

    protected function addFilters(QueryBuilder &$qb, array $filters) {
    }
}