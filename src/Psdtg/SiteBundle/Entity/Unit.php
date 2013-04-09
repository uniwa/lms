<?php

namespace Psdtg\SiteBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Psdtg\SiteBundle\Entity\Repositories\UnitsRepository")
 * @ExclusionPolicy("all")
 * @AccessType("public_method")
 */
class Unit
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @Expose
     */
    protected $mmId;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     * @Expose
     */
    protected $unitId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fyName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fyInitials;

    /**
     * @Expose
     */
    protected $fy;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    protected $registryNo;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    protected $streetAddress;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    protected $postalCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $categoryName;

    /**
     * @Expose
     */
    protected $category;

    public function getMmId() {
        return $this->mmId;
    }

    public function setMmId($mmId) {
        $this->mmId = $mmId;
    }

    public function getUnitId() {
        return $this->unitId;
    }

    public function setUnitId($unitId) {
        $this->unitId = $unitId;
    }

    public function getFyName() {
        return $this->fyName;
    }

    public function setFyName($fyName) {
        $this->fyName = $fyName;
    }

    public function getFyInitials() {
        return $this->fyInitials;
    }

    public function setFyInitials($fyInitials) {
        $this->fyInitials = $fyInitials;
    }

    public function getFy() {
        $fy = new Fy();
        $fy->setName($this->fyName);
        $fy->setInitials($this->fyInitials);
        return $fy;
    }

    public function setFy($fy) {
        $this->fyName = $fy->getName();
        $this->fyInitials = $fy->getInitials();
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getRegistryNo() {
        return $this->registryNo;
    }

    public function setRegistryNo($registryNo) {
        $this->registryNo = $registryNo;
    }

    public function getStreetAddress() {
        return $this->streetAddress;
    }

    public function setStreetAddress($streetAddress) {
        $this->streetAddress = $streetAddress;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }

    public function getCategoryName() {
        return $this->categoryName;
    }

    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }

    public function getCategory() {
        $category = new Category();
        $category->setName($this->categoryName);
        return $category;
    }

    public function setCategory($category) {
        $this->categoryName = $category->getName();
    }

    public function __toString() {
        return $this->getName();
    }
}