<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ActivateServiceRequest extends ExistingCircuitRequest
{
    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Unit")
     * @ORM\JoinColumn(name="mmId", referencedColumnName="mmId", onDelete="SET NULL")
     */
    protected $unit;
    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    protected $number;
    /**
     * @ORM\ManyToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\ConnectivityType")
     * @ORM\JoinColumn(name="connectivity_type_id", referencedColumnName="id")
     */
    protected $newConnectivityType;
    /**
     * @ORM\ManyToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile")
     * @ORM\JoinColumn(name="bandwidth_profile_id", referencedColumnName="id")
     */
    protected $newBandwidthProfile;

    public function getUnit() {
        return $this->unit;
    }

    public function setUnit($unit) {
        $this->unit = $unit;
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function getNewConnectivityType() {
        return $this->newConnectivityType;
    }

    public function setNewConnectivityType($newConnectivityType) {
        $this->newConnectivityType = $newConnectivityType;
    }

    public function getNewBandwidthProfile() {
        return $this->newBandwidthProfile;
    }

    public function setNewBandwidthProfile($newBandwidthProfile) {
        $this->newBandwidthProfile = $newBandwidthProfile;
    }
}