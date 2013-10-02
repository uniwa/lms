<?php

namespace Psdtg\SiteBundle\Entity\Circuits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * @ORM\Entity
 */
class PhoneCircuit extends Circuit
{
    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * @Expose
     */
    protected $type = self::TYPE_PSTN;
    const TYPE_PSTN = 'PSTN';
    const TYPE_ISDN = 'ISDN';

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * @Expose
     */
    protected $number;

    /**
     * @ORM\Column(type="boolean")
     * @Expose
     */
    protected $paidByPsd = false;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $bandwidth = self::ADSL_PROFILE_2MBPS;
    const ADSL_PROFILE_2MBPS = '2mbps';
    const ADSL_PROFILE_24MBPS = '24mbps';

    /**
     * @ORM\Column(type="integer")
     */
    protected $realspeed; // Ταχύτητα που κλείδωσε

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public static function getTypes() {
        return array(
            self::TYPE_PSTN => 'PSTN',
            self::TYPE_ISDN => 'ISDN',
        );
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function getBandwidth() {
        return $this->bandwidth;
    }

    public function setBandwidth($bandwidth) {
        $this->bandwidth = $bandwidth;
    }

    public function getRealspeed() {
        return $this->realspeed;
    }

    public function setRealspeed($realspeed) {
        $this->realspeed = $realspeed;
    }

    public function __toString() {
        if(isset($this->number)) {
            return 'Τ'.$this->number;
        } else {
            return 'Νέο Τηλεφωνικό Κύκλωμα';
        }
    }
}