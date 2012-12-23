<?php
namespace Psdtg\AdminBundle\Datagrid;

use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery as ProxyQuery;

class DnnaProxyQuery extends ProxyQuery {
    public function execute(array $params = array(), $hydrationMode = null) {
        return $this->queryBuilder->getQuery()->execute($params, $hydrationMode);
    }
}

?>