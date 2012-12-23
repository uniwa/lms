<?php

namespace Kp\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Kp\SiteBundle\Entity\Repositories\BasePageRepository")
 */
class Page extends BasePage
{
    /**
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(name="caption", type="string", nullable=true)
     */
    protected $caption;

    /**
     * @ORM\ManyToMany(targetEntity="Person")
     * @ORM\JoinTable(name="page_person",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $authors;

    /**
     * @ORM\Column(name="authorsPriority", type="string")
     */
    protected $authorsPriority = "";

    public function __construct() {
        $this->authors = new \Doctrine\Common\Collections\ArrayCollection();
        parent::__construct();
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getCaption() {
        return $this->caption;
    }

    public function setCaption($caption) {
        $this->caption = $caption;
    }

    public function getAuthors() {
        return $this->authors;
    }

    public function getAuthorsWithPrio() {
        // Authors random order
        $authors = clone $this->authors;
        $authors = $authors->toArray();
        shuffle($authors);
        $authors = new \Doctrine\Common\Collections\ArrayCollection($authors);
        // End authors random order
        if($this->authorsPriority != "") {
            $authorsPrioArray = explode(',', $this->authorsPriority);
            $result = new \Doctrine\Common\Collections\ArrayCollection();
            foreach($authorsPrioArray as $curPrioAuthor) {
                foreach($authors as $curIndex => &$curAuthor) {
                    if($curAuthor->getId() === trim($curPrioAuthor)) {
                        $result->add($curAuthor);
                        $authors->remove($curIndex);
                    }
                }
            }
            // Append the remaning elements to the result
            foreach($authors as $curAuthor) {
                $result->add($curAuthor);
            }
            return $result;
        } else {
            return $authors;
        }
    }

    public function setAuthors($authors) {
        $this->authors = $authors;
    }

    public function getAuthorsPriority() {
        return $this->authorsPriority;
    }

    public function setAuthorsPriority($authorsPriority) {
        $this->authorsPriority = $authorsPriority;
    }

    public function getShortTitle() {
        return $this->getTitle();
    }

    public function getFullCaption() {
        return $this->getCaption();
    }

    public function __toString() {
        if(is_string($this->getTitle())) {
            return $this->getTitle();
        } else {
            return $this->getId();
        }
    }
}