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
     * @ORM\OneToMany(targetEntity="Psdtg\SiteBundle\Entity\Services\ADSL", mappedBy="phoneCircuit")
     */
    protected $adsl;

    public function __construct() {
        $this->adsl = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function getAdsl() {
        return $this->adsl;
    }

    public function setAdsl($adsl) {
        $this->adsl = $adsl;
    }

    public function __toString() {
        if(isset($this->number)) {
            return 'Τ'.$this->number;
        } else {
            return 'Νέο Τηλεφωνικό Κύκλωμα';
        }
    }
}