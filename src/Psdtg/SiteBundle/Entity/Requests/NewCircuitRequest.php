<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class NewCircuitRequest extends Request
{
    /**
     * @ORM\ManyToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\CircuitType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $circuitType;

    const STATUS_OTEPENDING = 'OTEPENDING';
    const STATUS_LOCALOTE = 'LOCALOTE';
    const STATUS_CREWLEVEL = 'CREWLEVEL';
    const STATUS_INSTALLED = 'INSTALLED';

    public function getCircuitType() {
        return $this->circuitType;
    }

    public function setCircuitType($circuitType) {
        $this->circuitType = $circuitType;
    }

    public static function getStatuses() {
        return array(
            'PSD_CONTROL' => parent::getStatuses(),
            'OTE_CONTROL' => array(
                self::STATUS_OTEPENDING => self::STATUS_OTEPENDING,
                self::STATUS_LOCALOTE => self::STATUS_LOCALOTE,
                self::STATUS_CREWLEVEL => self::STATUS_CREWLEVEL,
            ),
            'ΟΤΕ_PSD_CONTROL' => array(
                self::STATUS_INSTALLED => self::STATUS_INSTALLED,
            ),
        );
    }
}