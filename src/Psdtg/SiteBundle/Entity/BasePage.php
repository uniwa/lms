<?php

namespace Kp\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="Pages")
 * @ORM\Entity(repositoryClass="Kp\SiteBundle\Entity\Repositories\BasePageRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"basepage" = "BasePage", "person" = "Person", "page" = "Page"})
 */
class BasePage
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string")
     */
    protected $id;

    /**
     * @ORM\Column(name="locale", type="string")
     */
    protected $locale = 'en';

    /**
     * @ORM\ManyToMany(targetEntity="MenuItem", inversedBy="taggedIn")
     */
    protected $tags;

    /**
     * @ORM\ManyToMany(targetEntity="Page")
     * @ORM\JoinTable(name="related_pages",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="related_page_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $relatedPages;

    /**
     * @ORM\Column(name="inSlideshow", type="boolean")
     */
    protected $inSlideshow = false;

    /**
     * @ORM\Column(name="slideorder", type="integer")
     */
    protected $slideorder = 0;

    /**
     * @ORM\Column(name="summary", type="text", nullable=true)
     */
    protected $summary;

    /**
     * @ORM\Column(name="content", type="text")
     */
    protected $content;

    /**
     * @ORM\Column(name="searchable", type="boolean")
     */
    protected $searchable = true;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    public function __construct() {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getType() {
        return 'page';
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    public function getTagsImploded() {
        $tagsArray = array();
        foreach($this->tags as $curTag) {
            $tagsArray[] = $curTag->getName();
        }
        return implode(', ', $tagsArray);
    }

    public function getRelatedPages() {
        return $this->relatedPages;
    }

    public function setRelatedPages($relatedPages) {
        $this->relatedPages = $relatedPages;
    }

    public function getRelatedPagesImploded() {
        $pagesArray = array();
        foreach($this->relatedPages as $curPage) {
            $pagesArray[] = $curPage->getTitle();
        }
        return implode(', ', $pagesArray);
    }

    public function getInSlideshow() {
        return $this->inSlideshow;
    }

    public function setInSlideshow($inSlideshow) {
        $this->inSlideshow = $inSlideshow;
    }

    public function getSlideorder() {
        return $this->slideorder;
    }

    public function setSlideorder($slideorder) {
        $this->slideorder = $slideorder;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getSearchable() {
        return $this->searchable;
    }

    public function setSearchable($searchable) {
        $this->searchable = $searchable;
    }

    public function isActive() {
        if($this->content > 50) {
            return true;
        } else {
            return false;
        }
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function setUpdated($updated) {
        $this->updated = $updated;
    }

    public function __toString() {
        return $this->getId();
    }
}