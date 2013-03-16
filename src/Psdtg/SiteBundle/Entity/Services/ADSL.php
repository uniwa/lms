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
 * Cookisto\SiteBundle\Entity\Dish
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @AccessType("public_method")
 */
class ADSL
{
    use TimestampableEntity;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    // ManyToOne
    protected $line; // ΜΠΟΡΕΙ ΝΑ ΕΙΝΑΙ NULL ΑΝ Η ΓΡΑΜΜΗ ΔΕΝ ΕΙΝΑΙ ΙΔΙΟΚΤΗΣΙΑΣ ΠΣΔ

    /**
     * @ORM\Column(name="status", type="string", length=100)
     * @Expose
     */
    protected $status;

    protected $profile;
    const PROFILE_2MBPS = '2mbps';
    const PROFILE_24MBPS = '24mbps';

    protected $realspeed; // Ταχύτητα που κλείδωσε

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

    public function getProfile() {
        return $this->profile;
    }

    public function setProfile($profile) {
        $this->profile = $profile;
    }

    public function getRealspeed() {
        return $this->realspeed;
    }

    public function setRealspeed($realspeed) {
        $this->realspeed = $realspeed;
    }

}