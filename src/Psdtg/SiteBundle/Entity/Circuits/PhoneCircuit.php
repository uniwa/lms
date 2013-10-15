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

use Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest;

/**
 * @ORM\Entity(repositoryClass="Psdtg\SiteBundle\Entity\Repositories\Circuits\PhoneCircuitsRepository")
 * @ExclusionPolicy("all")
 */
class PhoneCircuit extends Circuit
{
    /**
     * @ORM\ManyToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\ConnectivityType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * @Expose
     */
    protected $connectivityType;

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
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Expose
     */
    protected $bandwidth = self::ADSL_PROFILE_2MBPS;
    const ADSL_PROFILE_2MBPS = '2mbps';
    const ADSL_PROFILE_24MBPS = '24mbps';

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Expose
     */
    protected $realspeed; // Ταχύτητα που κλείδωσε

    /**
     * @Expose
     * @Accessor(getter="getFullName")
     */
    protected $fullName;

    public function getConnectivityType() {
        return $this->connectivityType;
    }

    public function setConnectivityType($connectivityType) {
        $this->connectivityType = $connectivityType;
    }

    public function getPaidByPsd() {
        return $this->paidByPsd;
    }

    public function setPaidByPsd($paidByPsd) {
        $this->paidByPsd = $paidByPsd;
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

    public function getFullName() {
        return $this->number.' '.$this->getUnit()->getName();
    }

    public function setFullName($fullName) {}

    public function populateWithRequestData(NewCircuitRequest $newcircuitrequest) {
        $this->setConnectivityType($newcircuitrequest->getConnectivityType());
        $this->setUnit($newcircuitrequest->getUnit());
        $this->setBandwidth($newcircuitrequest->getBandwidth());
        $this->setPaidByPsd(true);
        $this->setComments($newcircuitrequest->getComments());
    }

    public function __toString() {
        if(isset($this->number)) {
            return 'Τ'.$this->number;
        } else {
            return 'Νέο Τηλεπικοινωνιακό Κύκλωμα';
        }
    }
}