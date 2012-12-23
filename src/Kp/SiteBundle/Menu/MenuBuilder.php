<?php
namespace Kp\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    private $factory;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, EntityManager $entityManager)
    {
        $this->factory = $factory;
        $this->em = $entityManager;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array('childrenAttributes' => array('class' => 'main-nav')));
        $menu->setCurrentUri($request->getRequestUri());

        $arrayTree = $this->em->getRepository('KpSiteBundle:MenuItem')->childrenHierarchy();
        // Level 1
        usort($arrayTree[0]['__children'], array($this, "cmp_obj"));
        foreach($arrayTree[0]['__children'] as $level1) {
            // Special case for big menus
            $class= $level1['name'];
            $menu->addChild($level1['name'], array('route' => 'first', 'routeParameters' => array('level1' => $level1['page']), 'attributes' => array('class' => $class)));
            $menu[$level1['name']]->setExtra('page', $level1['page']);
            $menu[$level1['name']]->setExtra('id', $level1['id']);
            $menu[$level1['name']]->setExtra('searchResultsPage', $level1['searchResultsPage']);
            // Level2
            $menu[$level1['name']]->setChildrenAttributes(array('class' => 'subnav'));
            usort($level1['__children'], array($this, "cmp_obj"));
            foreach($level1['__children'] as $level2) {
                $menu[$level1['name']]->addChild($level2['name'], array('route' => 'second', 'routeParameters' => array('level1' => $level1['page'], 'level2' => $level2['page'])));
                $menu[$level1['name']][$level2['name']]->setExtra('page', $level2['page']);
                $menu[$level1['name']][$level2['name']]->setExtra('id', $level2['id']);
                $menu[$level1['name']][$level2['name']]->setExtra('searchResultsPage', $level2['searchResultsPage']);
                // Level 3
                $menu[$level1['name']][$level2['name']]->setChildrenAttributes(array('class' => 'subnav2'));
                usort($level2['__children'], array($this, "cmp_obj"));
                foreach($level2['__children'] as $level3) {
                    $menu[$level1['name']][$level2['name']]->addChild($level3['name'], array(
                        'route' => 'third',
                        'routeParameters' => array('level1' => $level1['page'], 'level2' => $level2['page'], 'level3' => $level3['page']
                    )));
                    $menu[$level1['name']][$level2['name']][$level3['name']]->setExtra('page', $level3['page']);
                    // Level 4
                    usort($level3['__children'], array($this, "cmp_obj"));
                    foreach($level3['__children'] as $level4) {
                        $menu[$level1['name']][$level2['name']][$level3['name']]->addChild($level4['name'], array(
                            'route' => 'fourth',
                            'routeParameters' => array('level1' => $level1['page'], 'level2' => $level2['page'], 'level3' => $level3['page'], 'level4' => $level4['page']
                        )));
                        $menu[$level1['name']][$level2['name']][$level3['name']][$level4['name']]->setExtra('page', $level4['page']);
                    }
                }
            }
        }

        return $menu;
    }

    /* This is the static comparing function: */
    static function cmp_obj($a, $b)
    {
        if($a['order'] == $b['order']) {
            $al = strtolower($a['name']);
            $bl = strtolower($b['name']);
            if ($al == $bl) {
                return 0;
            }
            return ($al > $bl) ? +1 : -1;
        } else {
            return $a['order'] - $b['order'];
        }
    }
}