<?php
namespace Psdtg\SiteBundle\Extension;

class TwigExtension extends \Twig_Extension {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function getFunctions() {
        return array(
            'getFrontPageCook'  => new \Twig_Function_Method($this, 'getFrontPageCook'),
            'isSupportedRegion'  => new \Twig_Function_Method($this, 'isSupportedRegion'),
        );
    }

    public function getFilters() {
        return array(
            'url_decode' => new \Twig_Filter_Method($this, 'url_decode'),
        );
    }

    public function url_decode($string) {
        return urldecode($string);
    }

    public function getName() {
        return 'psdtg.twig.extension';
    }


}
