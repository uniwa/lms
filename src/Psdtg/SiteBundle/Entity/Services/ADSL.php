<?php

namespace Psdtg\SiteBundle\Entity\Services;

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
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit", inversedBy="adsl")
     * @ORM\JoinColumn(name="circuit_id", referencedColumnName="id")
     */
    protected $phoneCircuit;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $profile = self::PROFILE_2MBPS;
    const PROFILE_2MBPS = '2mbps';
    const PROFILE_24MBPS = '24mbps';

    /**
     * @ORM\Column(type="integer")
     */
    protected $realspeed; // Ταχύτητα που κλείδωσε

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getPhoneCircuit() {
        return $this->phoneCircuit;
    }

    public function setPhoneCircuit($phoneCircuit) {
        $this->phoneCircuit = $phoneCircuit;
    }

    public function getProfile() {
        return $this->profile;
    }

    public function setProfile($profile) {
        $this->profile = $profile;
    }

    public static function getProfiles() {
        return array(
            self::PROFILE_24MBPS => '24mbps',
            self::PROFILE_2MBPS => '2mbps',
        );
    }

    public function getRealspeed() {
        return $this->realspeed;
    }

    public function setRealspeed($realspeed) {
        $this->realspeed = $realspeed;
    }

}