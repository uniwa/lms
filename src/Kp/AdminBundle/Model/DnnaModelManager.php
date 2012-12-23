<?php
namespace Kp\AdminBundle\Model;

use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Datagrid\DatagridInterface;

use Exporter\Source\DoctrineORMQuerySourceIterator;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager;

class DnnaModelManager extends ModelManager
{
    public function getDataSourceIterator(DatagridInterface $datagrid, array $fields, $firstResult = null, $maxResult = null)
    {
        $datagrid->buildPager();
        $query = $datagrid->getQuery();

        //$query->select('DISTINCT ' . $query->getRootAlias());
        $query->setFirstResult($firstResult);
        $query->setMaxResults($maxResult);

        return new DoctrineORMQuerySourceIterator($query instanceof ProxyQuery ? $query->getQuery() : $query, $fields);
    }

    public function find($class, $id)
    {
        if (!isset($id)) {
            return null;
        }
        $parents = class_parents($class);
        if(count($parents) > 0 && reset($parents) === 'Kp\UserBundle\Entity\User') {
            $obj = $this->getEntityManager($class)->getRepository($class)->findUsers(array('userid' => $id));
            $obj = $obj[0];
        } else {
            $obj = $this->getEntityManager($class)->getRepository($class)->find($id);
        }
        return $obj;
    }

    private function cast($obj, $to_class) {
        if(class_exists($to_class)) {
          $obj_in = serialize($obj);
          $obj_out = 'O:' . strlen($to_class) . ':"' . $to_class . '":' . substr($obj_in, $obj_in[2] + 7);
          return unserialize($obj_out);
        }
        else
          return false;
    }

}
