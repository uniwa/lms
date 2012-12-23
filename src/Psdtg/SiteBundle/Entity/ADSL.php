<?php

namespace Psdtg\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\SerializerBundle\Annotation\ExclusionPolicy;
use JMS\SerializerBundle\Annotation\Expose;
use JMS\SerializerBundle\Annotation\AccessType;
use JMS\SerializerBundle\Annotation\Accessor;
use JMS\SerializerBundle\Annotation\ReadOnly;

/**
 * Cookisto\SiteBundle\Entity\Dish
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @AccessType("public_method")
 */
class ADSL
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    // ManyToOne
    protected $line;

    /**
     * @ORM\Column(name="status", type="string", length=100)
     * @Expose
     */
    protected $status;

    /**
     * @ORM\Column(name="installDate", type="datetime")
     * @Expose
     */
    protected $installDate;

    // OneToMany
    protected $adslInstallRequests;

    protected $profile;
    const PROFILE_2MBPS = '2mbps';
    const PROFILE_24MBPS = '24mbps';

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLine() {
        return $this->line;
    }

    public function setLine($line) {
        $this->line = $line;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getInstallDate() {
        return $this->installDate;
    }

    public function setInstallDate($installDate) {
        $this->installDate = $installDate;
    }

    public function getAdslInstallRequests() {
        return $this->adslInstallRequests;
    }

    public function setAdslInstallRequests($adslInstallRequests) {
        $this->adslInstallRequests = $adslInstallRequests;
    }

    public function getProfile() {
        return $this->profile;
    }

    public function setProfile($profile) {
        $this->profile = $profile;
    }
}