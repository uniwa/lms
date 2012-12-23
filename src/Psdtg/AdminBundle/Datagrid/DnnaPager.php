<?php
namespace Psdtg\AdminBundle\Datagrid;

use Sonata\DoctrineORMAdminBundle\Datagrid\Pager;

class DnnaPager extends Pager
{
    public function computeNbResult()
    {
        $countQuery = clone $this->getQuery();

        if (count($this->getParameters()) > 0) {
            $countQuery->setParameters($this->getParameters());
        }

        $result = $countQuery->getQuery()->getResult();
        return count($result);
        //$countQuery->select(sprintf('count(DISTINCT %s.%s) as cnt', $countQuery->getRootAlias(), current($this->getCountColumn())));

        //return $countQuery->getSingleScalarResult();
    }
}
