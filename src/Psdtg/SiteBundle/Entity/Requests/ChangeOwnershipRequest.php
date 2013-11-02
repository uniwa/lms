<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ChangeOwnershipRequest extends ExistingCircuitRequest
{
    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Unit")
     * @ORM\JoinColumn(name="mmId", referencedColumnName="mmId", onDelete="SET NULL")
     */
    protected $unit;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Unit")
     * @ORM\JoinColumn(name="new_mmId", referencedColumnName="mmId", onDelete="SET NULL")
     */
    protected $newUnit;

    public function setCircuit($circuit) {
        $this->setUnit($circuit->getUnit());
        parent::setCircuit($circuit);
    }

    public function getUnit() {
        return $this->unit;
    }

    public function setUnit($unit) {
        $this->unit = $unit;
    }

    public function getNewUnit() {
        return $this->newUnit;
    }

    public function setNewUnit($newUnit) {
        $this->newUnit = $newUnit;
    }
}