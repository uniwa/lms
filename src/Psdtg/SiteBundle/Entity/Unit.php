<?php

namespace Psdtg\SiteBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\SerializerBundle\Annotation\ExclusionPolicy;
use JMS\SerializerBundle\Annotation\Expose;
use JMS\SerializerBundle\Annotation\AccessType;
use JMS\SerializerBundle\Annotation\Accessor;
use JMS\SerializerBundle\Annotation\ReadOnly;

/**
 * @ORM\Table()
 * @ORM\Entity
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
     * @ORM\Column(type="string")
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * @Expose
     */
    protected $registryNo;

    /**
     * @ORM\Column(type="string")
     * @Expose
     */
    protected $streetAddress;

    /**
     * @ORM\Column(type="string")
     * @Expose
     */
    protected $postalCode;

    public function getMmId() {
        return $this->mmId;
    }

    public function setMmId($mmId) {
        $this->mmId = $mmId;
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
}