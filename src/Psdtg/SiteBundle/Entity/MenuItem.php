<?php

namespace Kp\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\MaterializedPathRepository")
 * @ORM\Table(name="MenuItem")
 * @Gedmo\Tree(type="materializedPath")
 */
class MenuItem
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\TreePath(separator=">")
     * @ORM\Column(name="path", type="string", length=3000, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(name="level", type="integer", nullable=true)
     * @Gedmo\TreeLevel
     */
    private $lvl;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\Column(name="locale", type="string")
     */
    protected $locale = 'el';

    /**
     * @Gedmo\TreePathSource
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Page", mappedBy="tags")
     */
    protected $taggedIn;

    /**
     * @ORM\Column(name="searchResultsPage", type="boolean")
     */
    protected $searchResultsPage;

    /**
     * @ORM\Column(name="page", type="string")
     */
    protected $page;

    /**
     * @ORM\Column(name="isTag", type="boolean")
     */
    protected $isTag;

    /**
     * @ORM\Column(name="sortOrder", type="integer")
     */
    protected $order;

    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLvl() {
        return $this->lvl;
    }

    public function getPath() {
        return $this->path;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent(MenuItem $parent) {
        $this->parent = $parent;
    }

    public function getChildren() {
        return $this->children;
    }

    public function setChildren($children) {
        $this->children = $children;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getTaggedIn() {
        return $this->taggedIn;
    }

    public function setTaggedIn($taggedIn) {
        $this->taggedIn = $taggedIn;
    }

    public function getSearchResultsPage() {
        return $this->searchResultsPage;
    }

    public function setSearchResultsPage($searchResultsPage) {
        $this->searchResultsPage = $searchResultsPage;
    }

    public function getPage() {
        return $this->page;
    }

    public function setPage($page) {
        $this->page = $page;
    }

    public function getIsTag() {
        return $this->isTag;
    }

    public function setIsTag($isTag) {
        $this->isTag = $isTag;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    public function __toString()
    {
        $prefix = "";
        for ($i=2; $i<= $this->lvl; $i++){
            $prefix .= "&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        return $prefix . $this->name;
    }

    public function getLaveledTitle()
    {
        return (string)$this;
    }
}