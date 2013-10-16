<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Psdtg\SiteBundle\Entity\Circuits\PhoneCircuit;

/**
 * @ORM\Entity
 */
class NewCircuitRequest extends Request
{
    /**
     * @ORM\ManyToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\ConnectivityType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $connectivityType;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Unit")
     * @ORM\JoinColumn(name="mmId", referencedColumnName="mmId", onDelete="SET NULL")
     */
    protected $unit;

    /**
     * @ORM\Column(name="tech_factsheet_no", type="string", length=100)
     */
    protected $techFactsheetNo;

    const STATUS_OTEPENDING = 'OTEPENDING';
    const STATUS_LOCALOTE = 'LOCALOTE';
    const STATUS_CREWLEVEL = 'CREWLEVEL';
    const STATUS_WAITINGCREW = 'WAITINGCREW';
    const STATUS_INSTALLED = 'INSTALLED';

    /**
     * @ORM\ManyToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\BandwidthProfile")
     * @ORM\JoinColumn(name="bandwidth_profile_id", referencedColumnName="id")
     */
    protected $bandwidthProfile;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\Circuit", mappedBy="newCircuitRequest")
     */
    protected $circuit;

    public function getConnectivityType() {
        return $this->connectivityType;
    }

    public function setConnectivityType($connectivityType) {
        $this->connectivityType = $connectivityType;
    }

    public function getUnit() {
        return $this->unit;
    }

    public function setUnit($unit) {
        $this->unit = $unit;
    }

    public function getTechFactsheetNo() {
        return $this->techFactsheetNo;
    }

    public function setTechFactsheetNo($techFactsheetNo) {
        $this->techFactsheetNo = $techFactsheetNo;
    }

    public function getBandwidthProfile() {
        return $this->bandwidthProfile;
    }

    public function setBandwidthProfile($bandwidthProfile) {
        $this->bandwidthProfile = $bandwidthProfile;
    }

    public function getCircuit() {
        return $this->circuit;
    }

    public function setCircuit($circuit) {
        $this->circuit = $circuit;
    }

    public static function getStatuses() {
        return array(
            'PSD_CONTROL' => parent::getStatuses(),
            'OTE_CONTROL' => array(
                self::STATUS_OTEPENDING => self::STATUS_OTEPENDING,
                self::STATUS_LOCALOTE => self::STATUS_LOCALOTE,
                self::STATUS_CREWLEVEL => self::STATUS_CREWLEVEL,
                self::STATUS_WAITINGCREW => self::STATUS_WAITINGCREW,
            ),
            'ΟΤΕ_PSD_CONTROL' => array(
                self::STATUS_INSTALLED => self::STATUS_INSTALLED,
            ),
        );
    }
}