<?php

namespace Kp\SiteBundle\Extension;

use Kp\SiteBundle\Entity\BasePage;
use Kp\SiteBundle\Entity\MenuItem;

class FindRoute {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function findRoute($page) {
        $arrayTree = $this->container->get('doctrine')->getEntityManager()->getRepository('KpSiteBundle:MenuItem')->childrenHierarchy();
        // If the page is part of a menu then return the appropriate route
        $this->routefound = false;
        $result = $this->findRouteThroughMenuItem($arrayTree[0], array(), $page);
        if($result != null) {
            $route = $result['route'];
            unset($result['level0']);
            unset($result['route']);
            return array(
                'route' => $route,
                'routeParameters' => $result
            );
        }

        // If the page isn't part of a menu then try to determine its route from its tags (search result menus first)
        $result = $this->findRouteThroughTags($page);
        if($result != null) {
            $route = $result['route'];
            unset($result['route']);
            return array(
                'route' => $route,
                'routeParameters' => $result
            );
        }

        // If none exist then show the page as a level1
        if(is_object($page)) {
            $page = $page->getId();
        }
        return array(
            'route' => 'first',
            'routeParameters' => array('level1' => $page)
        );
    }

    protected function findRouteThroughMenuItem($root, array $return, $page) {
        $return['level'.count($return)] = $root['page'];
        foreach($root['__children'] as $curItem) {
            if(strtolower($curItem['page']) === strtolower($page->getId())) {
                $return['level'.count($return)] = $curItem['page'];
                if(count($return) == 2) {
                    $return['route'] = 'first';
                } else if(count($return) == 3) {
                    $return['route'] = 'second';
                } else if(count($return) == 4) {
                    $return['route'] = 'third';
                } else if(count($return) >= 5) {
                    $return['route'] = 'fourth';
                }
                $this->routefound = $return;
                return;
            }
            if(count($curItem['__children']) > 0) {
                $this->findRouteThroughMenuItem($curItem, $return, $page);
            }
        }
        return $this->routefound;
    }

    protected function findRouteThroughTags($page) {
        foreach($page->getTags() as $curTag) {
            if($curTag->getSearchResultsPage()) {
                // Get depth and related parameters
                $depth = $this->getDepth($curTag) + 1;
                $return = array();
                $return['level'.$depth] = $page->getId();
                $depth--;
                while($curTag->getParent() != null) {
                    $return['level'.$depth] = $curTag->getPage();
                    $curTag = $curTag->getParent();
                    $depth--;
                }
                if(count($return) == 1) {
                    $return['route'] = 'first';
                } else if(count($return) == 2) {
                    $return['route'] = 'second';
                } else if(count($return) >= 3) {
                    $return['route'] = 'third';
                } else if(count($return) >= 4) {
                    $return['route'] = 'forth';
                }
                return $return;
            }
        }
        return null;
    }

    private function getDepth($curTag) {
        $depth = 0;
        while($curTag->getParent() != null) {
            $curTag = $curTag->getParent();
            $depth++;
        }
        return $depth;
    }

    public function findKnpMenuItem($id) {
        $haystack = $this->container->get('kp_main.menu.main');
        $menuItem = $this->findKnpMenuItemRecursive($id, $haystack);
        if($menuItem != false) {
            return $menuItem;
        } else {
            // If the page isn't part of a menu then try to determine its route from its tags (search result menus first)
            $page = $this->container->get('doctrine')->getEntityManager()->getRepository('KpSiteBundle:BasePage')->find($id);
            if($page == null) {
                return null;
            }
            $result = $this->findKnpMenuItemThroughTags($page, $haystack);
            if($result != null) {
                return $result;
            }
            return null;
        }
    }

    protected function findKnpMenuItemRecursive($needle, $haystack)
    {
        if( !$haystack instanceof \Knp\Menu\MenuItem ) {
            return false;
        }
        foreach( $haystack as $val ) {
            if(strtolower($val->getExtra('page')) === strtolower($needle)) {
                return $val;
            } else if( $val instanceof \Knp\Menu\MenuItem && $item = $this->findKnpMenuItemRecursive($needle, $val)) {
                return $item;
            }
        }
        return false;
    }

    protected function findKnpMenuItemThroughTags(BasePage $page, $haystack) {
        foreach($page->getTags() as $curTag) {
            if($curTag->getSearchResultsPage()) {
                $this->knpmenuitemfoundthroughtag = false;
                $result = $this->findKnpMenuItemThroughMenuItemRecursive($curTag, $haystack);
                if($result != false) {
                    return $result;
                }
            }
        }
        return null;
    }

    protected function findKnpMenuItemThroughMenuItemRecursive(MenuItem $menuItem, $haystack) {
        foreach($haystack->getChildren() as $curChild) {
            if($curChild->getExtra('id') == $menuItem->getId()) {
                $this->knpmenuitemfoundthroughtag = $curChild;
                return;
            }
            if($curChild->hasChildren()) {
                $this->findKnpMenuItemThroughMenuItemRecursive($menuItem, $curChild);
            }
        }
        return $this->knpmenuitemfoundthroughtag;
    }
}