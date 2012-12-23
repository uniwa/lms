<?php

namespace Kp\SiteBundle\Extension;

class MyTwigExtension extends \Twig_Extension {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
        $this->container->get('twig.loader')->addPath(__DIR__.'/../../../../vendor/symfony/symfony/src/Symfony/Bridge/Twig/Resources/views/Form');
    }

    public function getFilters() {
        return array(
            'round'  => new \Twig_Filter_Method($this, 'round'),
            'shorten' => new \Twig_Filter_Method($this, 'shorten'),
            'name' => new \Twig_Filter_Method($this, 'name'),
            'first' => new \Twig_Filter_Method($this, 'first'),
            'debug' => new \Twig_Filter_Method($this, 'debug'),
            'get_class' => new \Twig_Filter_Method($this, 'getClass'),
            'contains' => new \Twig_Filter_Method($this, 'contains'),
            'getAuthors' => new \Twig_Filter_Method($this, 'getAuthors'),
            'getPageTags' => new \Twig_Filter_Method($this, 'getPageTags'),
            'getTaggedPages' => new \Twig_Filter_Method($this, 'getTaggedPages'),
            'getPage' => new \Twig_Filter_Method($this, 'getPage'),
            'inBinder' => new \Twig_Filter_Method($this, 'inBinder'),
        );
    }

    public function getFunctions() {
        return array(
            'host'  => new \Twig_Function_Method($this, 'host'),
            'getReferer' => new \Twig_Function_Method($this, 'getReferer'),
            'getDepth' => new \Twig_Function_Method($this, 'getDepth'),
            'getRootItem' => new \Twig_Function_Method($this, 'getRootItem'),
            'getL1Parent' => new \Twig_Function_Method($this, 'getL1Parent'),
            'getL2Parent' => new \Twig_Function_Method($this, 'getL2Parent'),
            'getL3Parent' => new \Twig_Function_Method($this, 'getL3Parent'),
            'findRoute' => new \Twig_Function_Method($this, 'findRoute'),
            'findMenuItem' => new \Twig_Function_Method($this, 'findMenuItem'),
            'getBinderPages' => new \Twig_Function_Method($this, 'getBinderPages'),
        );
    }

    public function round($number) {

        return round($number);
    }

    public function host() {
        if($this->container->isScopeActive('request')) {
            $request = $this->container->get('request');
            return $request->getScheme().'://'.$request->getHttpHost();
        } else {
            return 'http://www.kp-lf.com';
        }
    }

    public function getReferer() {
        return $this->container->get('lreferer')->getReferer();
    }

    public function getName()
    {
        return 'my_twig_extension';
    }

    public function name($parts){
      return $parts[0] . ' ' . substr($parts[1], 0, 1).'.';
    }

    public function shorten($text, $length)
    {
      return (strlen($text) > $length) ?
        substr($text, 0, $length) . '…' :
        $text;
    }

    public function debug($var){
      return '<pre>'.var_dump($var).'</pre>';
    }

    public function first($var) {
        return reset($var);
    }

    public function getClass($var){
        if(is_object($var)) {
            return get_class($var);
        } else {
            return '';
        }
    }

    public function contains($var, $string) {
        if(strpos($var, $string) !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function getDepth($menu) {
        $i = 0;
        while($menu->getParent() != null) {
            $menu = $menu->getParent();
            $i++;
        }
        return $i;
    }

    public function getRootItem($menu) {
        while($menu->getParent() != null) {
            $menu = $menu->getParent();
        }
        return $menu;
    }

    public function getL1Parent($menu) {
        $items = array();
        $i = 0;
        $items[] = $menu;
        while($menu->getParent() != null) {
            $menu = $menu->getParent();
            $items[] = $menu;
        }
        if(isset($items[count($items) - 2])) {
            return $items[count($items) - 2];
        } else {
            return null;
        }
    }

    public function getL2Parent($menu) {
        $items = array();
        $i = 0;
        $items[] = $menu;
        while($menu->getParent() != null) {
            $menu = $menu->getParent();
            $items[] = $menu;
        }
        if(isset($items[count($items) - 3])) {
            return $items[count($items) - 3];
        } else {
            return null;
        }
    }

    public function getL3Parent($menu) {
        $items = array();
        $i = 0;
        $items[] = $menu;
        while($menu->getParent() != null) {
            $menu = $menu->getParent();
            $items[] = $menu;
        }
        if(isset($items[count($items) - 4])) {
            return $items[count($items) - 4];
        } else {
            return null;
        }
    }

    public function getAuthors(\Kp\SiteBundle\Entity\BasePage $page) {
        // If its a person page then it can have no authors
        if($page instanceof \Kp\SiteBundle\Entity\Person) {
            return array();
        }
        /*$authors = array();
        foreach($page->getTags() as $curTag) {
            $relatedPage = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\BasePage')->find($curTag->getPage());
            if(isset($relatedPage) && $relatedPage instanceof \Kp\SiteBundle\Entity\Person) {
                $authors[] = $relatedPage;
            }
        }*/
        return $page->getAuthorsWithPrio();
    }

    public function getPageTags(\Kp\SiteBundle\Entity\BasePage $page) {
        $pagetags = array();
        foreach($page->getTags() as $curTag) {
            $relatedPage = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\BasePage')->find($curTag->getPage());
            if(isset($relatedPage) && $relatedPage instanceof \Kp\SiteBundle\Entity\Page) {
                $pagetags[] = $curTag;
            }
        }
        return $pagetags;
    }

    public function getTaggedPages(\Knp\Menu\MenuItem $menu) {
        if($menu->getExtra('id') == null) {
            return array();
        }
        $menuItem = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\MenuItem')->find($menu->getExtra('id'));
        return $menuItem->getTaggedIn();
    }

    public function getPage($menuitem) {
        if($menuitem instanceof \Knp\Menu\MenuItem) {
            $pageid = $menuitem->getExtra('page');
        } else if($menuitem instanceof \Kp\SiteBundle\Entity\MenuItem) {
            $pageid = $menuitem->getPage();
        } else {
            throw new \Exception('Wrong type');
        }
        $page = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\BasePage')->find($pageid);
        if($page == null) {
            $page = new \Kp\SiteBundle\Entity\Page();
            $page->setId('PageNotFound');
            $page->setTitle('Page not found');
        }
        return $page;
    }

    public function findRoute($page) {
        return $this->container->get('kp.findroute')->findRoute($page);
    }

    public function findMenuItem($page) {
        return $this->container->get('kp.findroute')->findKnpMenuItem($page->getId());
    }

    public function inBinder($page) {
        if($this->container->isScopeActive('request')) {
            $session = $this->container->get('session');
            $binder = $session->get('binder', array());
            if(in_array($page->getId(), $binder)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getBinderPages() {
        if($this->container->isScopeActive('request')) {
            $session = $this->container->get('session');
            $binder = $session->get('binder', array());
            return $binder;
        } else {
            return array();
        }
    }
}