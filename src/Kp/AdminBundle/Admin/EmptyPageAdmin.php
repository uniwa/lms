<?php
namespace Kp\AdminBundle\Admin;

use Kp\AdminBundle\Datagrid\DnnaProxyQuery;

class EmptyPageAdmin extends PageAdmin
{
    protected $baseRouteName = 'admin_kp_site_page_emptypage';
    protected $baseRoutePattern = 'emptypages';
    /*protected $datagridValues = array(
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'becameCookDate' // name of the ordered field (default = the model id
        );*/

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere('LENGTH(o.content) <= :minchars');
        $query->setParameter('minchars', 50);

        return $query;
    }
}