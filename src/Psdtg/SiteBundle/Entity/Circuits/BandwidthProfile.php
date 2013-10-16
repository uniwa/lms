<?php

namespace Psdtg\SiteBundle\Entity\Circuits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class BandwidthProfile
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\ConnectivityType", inversedBy="bandwidthProfiles")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $connectivityType;

    /**
     * @ORM\Column(type="string")
     */
    protected $bandwidth;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getConnectivityType() {
        return $this->connectivityType;
    }

    public function setConnectivityType($connectivityType) {
        $this->connectivityType = $connectivityType;
    }

    public function getBandwidth() {
        return $this->bandwidth;
    }

    public function setBandwidth($bandwidth) {
        $this->bandwidth = $bandwidth;
    }

    public function __toString() {
        return $this->getBandwidth();
    }
}