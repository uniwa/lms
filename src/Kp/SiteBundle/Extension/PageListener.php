<?php
namespace Kp\SiteBundle\Extension;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Kp\SiteBundle\Entity\BasePage as Page;
use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;
use EWZ\Bundle\SearchBundle\Lucene\Document;
use EWZ\Bundle\SearchBundle\Lucene\Field;

class PageListener {
    protected $container;
    protected $search;

    public function __construct($container) {
        $this->container = $container;
        $this->search = $this->container->get('ewz_search.lucene');
    }

    public function prePersist(LifecycleEventArgs $lcea) {
        $page = $lcea->getEntity();
        if(!$page instanceof Page) {
            return;
        }

        $em = $lcea->getEntityManager();
        $this->addToIndex($page);
        $this->search->updateIndex();
    }

    // Detect deletion
    public function preUpdate(PreUpdateEventArgs $eventArgs) {
        $page = $eventArgs->getEntity();
        if(!$page instanceof Page) {
            return;
        }

        $this->removeFromIndex($page);
        $this->addToIndex($page);
        $this->search->updateIndex();
    }

    public function preRemove(LifecycleEventArgs $eventArgs) {
        $page = $eventArgs->getEntity();
        if(!$page instanceof Page) {
            return;
        }

        $em = $eventArgs->getEntityManager();
        $this->removeFromIndex($page);
        $this->search->updateIndex();
    }

    public function addToIndex(Page $page) {
        if($page->getSearchable() == true) {
            $document = new Document();
            $document->addField(Field::keyword('key', $page->getId()));
            $document->addField(Field::text('title', $page->getTitle()));
            $document->addField(Field::text('caption', $page->getTitle()));
            // Authors
            $twig = $this->container->get('kp.twig.extension');
            $i = 0;
            foreach($twig->getAuthors($page) as $curAuthor) {
                $document->addField(Field::text('authorname'.$i++, $curAuthor->getName()));
                $document->addField(Field::text('authorsurname'.$i++, $curAuthor->getSurName()));
            }
            // End authors
            $document->addField(Field::unstored('content', strip_tags($page->getContent())));
            $document->addField(Field::UnIndexed('pageid', $page->getId()));
            // Get type (expertise, people, perspective, or other)
            $menuItem = $this->container->get('doctrine')->getEntityManager()->getRepository('Kp\SiteBundle\Entity\MenuItem')->findOneBy(array(
                'page' => $page->getId()
            ));
            if(isset($menuItem)) {
                if(strtolower($twig->getL1Parent($menuItem)->getPage()) === 'expertise') {
                    $type = 'expertise';
                } else if(strtolower($twig->getL1Parent($menuItem)->getPage()) === 'people') {
                    $type = 'people';
                } else if(strtolower($twig->getL1Parent($menuItem)->getPage()) === 'perspectives') {
                    $type = 'perspectives';
                } else {
                    $type = 'other';
                }
            } else {
                $type = 'other';
            }
            $document->addField(Field::keyword('type', $type));
            // A universal parameter to fetch all pages
            $document->addField(Field::text('saved','yes'));
            $this->search->addDocument($document);
        }
    }

    public function removeFromIndex(Page $page) {
        $results = $this->search->find('key:'.$page->getId());
        foreach($results as $curResult) {
            $this->search->getIndex()->delete($curResult);
        }
    }
}