<?php

namespace Kp\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Zend\Search\Lucene\Search\Query\Boolean;
use Zend\Search\Lucene\Index\Term;
use Zend\Search\Lucene\Search\Query\Term as QueryTerm;
use Zend\Search\Lucene\Search\QueryParser;

class DefaultController extends Controller {
    /**
     * @Route("/", name="home")
     * @Template
     */
    public function indexAction() {
        $overview = $this->container->get('doctrine')->getEntityManager()->getRepository('KpSiteBundle:Page')->find('home_bottom_centre');
        $contact = $this->container->get('doctrine')->getEntityManager()->getRepository('KpSiteBundle:Page')->find('home_contact');
        $slides = $this->container->get('doctrine')->getEntityManager()->getRepository('KpSiteBundle:BasePage')->findPages(array(
            'inSlideshow' => true,
            'sortBy' => 'slideorder, rand',
            'sortDirection' => 'ASC',
        ), false);
        $news = $this->container->get('doctrine')->getEntityManager()->getRepository('KpSiteBundle:BasePage')->findPages(array(
            'sortBy' => 'created',
            'sortDirection' => 'DESC',
            'limit' => 3,
            'minchars' => 50,
        ), false);
        return $this->render('KpSiteBundle:Default:index.html.twig', array(
            'overview' => $overview,
            'contact' => $contact,
            'slides' => $slides,
            'news' => $news,
        ));
    }

    /**
     * @Route("/search/", name="search")
     * @Template
     */
    public function searchAction() {
        // Search by term
        $searchstr = $this->container->get('request')->get('search');
        $strQuery = QueryParser::parse($searchstr);
        $refine = $this->container->get('request')->get('refine', '');
        $query = new Boolean();
        $query->addSubquery($strQuery, true /* required */);
        if($refine != null) {
            $refineTerm  = new Term($refine , 'type');
            $refineQuery = new QueryTerm($refineTerm);
            $query->addSubquery($refineQuery, true /* required */);
        }
        $search = $this->get('ewz_search.lucene');
        $searchresults = $search->find($query);
        $adapter = new ArrayAdapter($searchresults);
        $searchresults = new Pagerfanta($adapter);
        // Paging options
        $searchresults->setMaxPerPage(10); // 10 by default
        $searchresults->setCurrentPage($this->container->get('request')->get('page', 1));
        // Load the appropriate pages
        $pageresults = array();
        foreach($searchresults as $curResult) {
            $pageresults[] = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\BasePage')->find($curResult->key);
        }
        return $this->render('KpSiteBundle:Search:search.html.twig', array(
            'pagemenu' => $this->get('kp_main.menu.main')->getCurrentItem(),
            'search' => $searchstr,
            'results' => $pageresults,
            'paginator' => $searchresults,
            'allowRefine' => true,
        ));
    }

    /**
     * @Route("/submit_contact_form", name="submit_contact_form")
     * @Template
     */
    public function submitContactFormAction() {
        $referer = $this->getRequest()->headers->get('referer');
        if($this->container->get('request')->get('email') == null || $this->container->get('request')->get('fname') == null) {
            // Fail
            $this->get('session')->setFlash('notice', 'There was an error submitting your inquiry. Did you fill every field?');
            return new RedirectResponse($referer ? $referer : $this->generateUrl('home'));
        }
        $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject('Contact Form Request From : '.$this->container->get('request')->get('email'))
                ->setFrom('contact@kp-lf.com', 'KF Law Contact Form')
                ->setReplyTo($this->container->get('request')->get('email'))
                ->setTo('cklissouras@kp-lf.com')
                //->setTo('dnna@dnna.gr')
                ->setBody($this->container->get('request')->get('fname')."<BR />".$this->container->get('request')->get('lname')."<BR />".$this->container->get('request')->get('subject')."<BR />".$this->container->get('request')->get('organisation')."<BR />".$this->container->get('request')->get('email')."<BR />".$this->container->get('request')->get('text'));
        $this->container->get('mailer')->send($message);
        // Success
        $this->get('session')->setFlash('notice', 'Thank you for your inquiry. We will review it and revert as appropriate very shortly.');
        return new RedirectResponse($referer ? $referer : $this->generateUrl('home'));
    }

    /**
     * @Route("/binder", name="binder")
     * @Template
     */
    public function binderAction() {
        return $this->render('KpSiteBundle:Binder:binder.html.twig', array(
            'pages' => $this->getBinderPages()
        ));
    }

    /**
     * @Route("/binder/add/{pageid}", name="add_to_binder")
     * @Template
     */
    public function addToBinderAction($pageid) {
        $referer = $this->getRequest()->headers->get('referer');
        $binderpages = $this->container->get('session')->get('binder', array());
        if(!in_array($pageid, $binderpages)) {
            $binderpages[] = $pageid;
        }
        $this->container->get('session')->set('binder', $binderpages);
        return new RedirectResponse($referer ? $referer : $this->generateUrl('home'));
    }

    /**
     * @Route("/binder/remove/{pageid}", name="remove_from_binder")
     * @Template
     */
    public function removeFromBinderAction($pageid) {
        $referer = $this->getRequest()->headers->get('referer');
        $binderpages = $this->container->get('session')->get('binder', array());
        if(($key = array_search($pageid, $binderpages)) !== false) {
            unset($binderpages[$key]);
        }
        $this->container->get('session')->set('binder', $binderpages);
        return new RedirectResponse($referer ? $referer : $this->generateUrl('home'));
    }

    /**
     * @Route("/binder/getpdf", name="binder_get_pdf")
     * @Template
     */
    public function binderGetPdfAction() {
        if ($this->container->has('profiler')) {
            $this->container->get('profiler')->disable();
        }
        if($this->get('request')->get('html') === 'true') {
            return $this->render('KpSiteBundle:Binder:binderpdf.html.twig', array(
                'pages' => $this->getBinderPages()
            ));
        }
        /*return new Response(
            $this->container->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );*/
        $u = $this->generateUrl('binder_get_pdf', array('html' => 'true', 'pages' => urlencode(implode('-',$this->getBinderPageIds()))));
        return new Response(
            //file_get_contents('http://'.$this->getRequest()->getHost().$u)
            $this->get('knp_snappy.pdf')->getOutput('http://'.$this->getRequest()->getHost().$u),
            200,
            array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="file.pdf"'
            )
        );
    }

    /**
     * @Route("/change_lang/{lang}", name="change_lang")
     * @Template
     */
    public function changeLangAction($lang) {
        $this->get('session')->set('_locale', $lang);
        $referer = $this->getRequest()->headers->get('referer');

        return new RedirectResponse($referer ? $referer : $this->generateUrl('home'));
    }

    /**
     * @Route("/{level1}/", name="first")
     * @Template
     */

    public function firstAction($level1) {
        return $this->chooseTemplate($level1, 'first');
    }

    /**
     * @Route("/{level1}/{level2}/", name="second")
     * @Template
     */
    public function secondAction($level1, $level2) {
        return $this->chooseTemplate($level2, 'second');
    }

    /**
     * @Route("/{level1}/{level2}/{level3}/", name="third")
     * @Template
     */
    public function thirdAction($level1, $level2, $level3) {
        return $this->chooseTemplate($level3, 'third');
    }

    /**
     * @Route("/{level1}/{level2}/{level3}/{level4}/", name="fourth")
     * @Template
     */
    public function fourthAction($level1, $level2, $level3, $level4) {
        return $this->chooseTemplate($level4, 'third');
    }

    protected function getBinderPageIds() {
        if($this->get('request')->get('pages') == null) {
            $pageids = $this->get('session')->get('binder');
        } else {
            $pageids = explode('-', urldecode($this->get('request')->get('pages')));
        }
        return $pageids;
    }

    protected function getBinderPages() {
        $pages = array();
        if($this->get('request')->get('pages') == null) {
            $pageids = $this->get('session')->get('binder');
        } else {
            $pageids = explode('-', urldecode($this->get('request')->get('pages')));
        }
        foreach($pageids as $curPage) {
            $pages[] = $this->getPage($curPage);
        }
        return $pages;
    }

    protected function getPage($id) {
        $page = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\BasePage')->findOneBy(array(
            'id' => $id
        ));
        if($page == null) {
            $page = new \Kp\SiteBundle\Entity\Page();
            $page->setId('PageNotFound');
            $page->setTitle('Page not found');
        }
        return $page;
    }

    protected function getMenuItemByPage($page) {
        $menuItem = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\MenuItem')->findOneBy(array(
            'page' => $page
        ));
        return $menuItem;
    }

    protected function getRelatedPerspectives($page) {
        $menuitem = $this->getMenuItemByPage($page);
        if($menuitem != null) {
            $ids = array();
            foreach($menuitem->getTaggedIn() as $curPage) {
                $curPageRoute = $this->get('kp.findroute')->findRoute($curPage);
                if(strtolower($curPageRoute['routeParameters']['level1']) === 'perspectives') {
                    $ids[] = "'".$curPage->getId()."'";
                }
            }
            $news = $this->container->get('doctrine')->getEntityManager()->getRepository('KpSiteBundle:BasePage')->findPages(array(
                'id_pool' => $ids,
                'sortBy' => 'created',
                'sortDirection' => 'DESC',
                'limit' => 3
            ), false);
        } else {
            $news = array();
        }
        return $news;
    }

    protected function chooseTemplate($page, $defaultLevel) {
        // Search page
        if($this->getMenuItemByPage($page) != null && $this->getMenuItemByPage($page)->getSearchResultsPage()) {
            $results = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\BasePage')->findPages(array(
                'search' => $this->getMenuItemByPage($page)->getName(),
            ));
            return $this->render('KpSiteBundle:Search:search.html.twig', array(
                'pagemenu' => $this->get('kp_main.menu.main')->getCurrentItem(),
                'results' => $results,
                'searchTitle' => $this->getMenuItemByPage($page)->getName(),
                'paginator' => $results,
                'allowRefine' => false,
            ));
        }
        // Cv page
        if($this->getPage($page) instanceof \Kp\SiteBundle\Entity\Person) {
            return $this->render('KpSiteBundle:Cv:cv.html.twig', array(
                'page' => $this->getPage($page),
                'pagemenu' => $this->get('kp_main.menu.main')->getCurrentItem(),
            ));
        }
        // Pages with no menu automatically become 3rd level
        if($this->get('kp_main.menu.main')->getCurrentItem() == null) {
            // Try to determine the previous level item from the URI
            $urlparts = explode('/', $this->container->get('request')->getRequestUri());
            // Strip the empty parts from the end
            while(count($urlparts) > 0 && ($urlparts[count($urlparts) - 1] == ''/* || $urlparts[count($urlparts) - 1] === $page)*/)) {
                unset($urlparts[count($urlparts) - 1]);
            }
            $menuItem = $this->get('kp.findroute')->findKnpMenuItem($urlparts[count($urlparts) - 1]);
            // If a menu item couldn't be found then use home
            if($menuItem == null) {
                $menuItem = $this->get('kp_main.menu.home');
                $menuItem = $menuItem['HOME'];
            }
            // Create a submenu for the current item
            $menuItem = clone $menuItem; // Clone the menu item so that it doesn't appear in the top menu
            $title = $this->getPage($page)->getTitle();
            $routeResult = $this->get('kp.findroute')->findRoute($this->getPage($page));
            $menuItem->addChild($title, array(
                'route' => $routeResult['route'],
                'routeParameters' => $routeResult['routeParameters']
            ));
            $menuItem[$title]->setCurrent(true);
            $menuItem = $menuItem[$title];

            // Render the page
            return $this->render('KpSiteBundle:Third:third.html.twig', array(
                'page' => $this->getPage($page),
                'pagemenu' => $menuItem,
                'news' => $this->getRelatedPerspectives($page),
            ));
        }
        // First level
        if($defaultLevel === 'first') {
            return $this->render('KpSiteBundle:First:first.html.twig', array(
                'page' => $this->getPage($page),
                'pagemenu' => $this->get('kp_main.menu.main')->getCurrentItem(),
            ));
        }
        // Second level
        else if($defaultLevel === 'second') {
            return $this->render('KpSiteBundle:Second:second.html.twig', array(
                'page' => $this->getPage($page),
                'pagemenu' => $this->get('kp_main.menu.main')->getCurrentItem(),
            ));
        }
        // Third level
        else if($defaultLevel === 'third') {
            return $this->render('KpSiteBundle:Third:third.html.twig', array(
                'page' => $this->getPage($page),
                'pagemenu' => $this->get('kp_main.menu.main')->getCurrentItem(),
                'news' => $this->getRelatedPerspectives($page),
            ));
        }
    }
}
