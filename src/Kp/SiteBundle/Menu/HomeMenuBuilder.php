<?php
namespace Kp\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class HomeMenuBuilder
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

    public function createHomeMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array('childrenAttributes' => array('class' => 'main-nav' )));
        $menu->setExtra('hideHome', true);

        $menu->addChild('HOME', array('route' => 'home', 'attributes' => array('class' => 'home')));
        $menu['HOME']->setExtra('page', 'home');
        $menu['HOME']->setExtra('id', '0');
        $menu['HOME']->setExtra('searchResultsPage', false);

        return $menu;
    }
}