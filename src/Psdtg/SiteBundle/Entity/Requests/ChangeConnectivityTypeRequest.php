<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ChangeConnectivityTypeRequest extends ExistingCircuitRequest
{
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