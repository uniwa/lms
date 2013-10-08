<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

/** @MappedSuperclass */
class ExistingCircuitRequest extends Request
{
    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\Circuit")
     * @ORM\JoinColumn(name="circuitId", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $circuit;

    public function getCircuit() {
        return $this->circuit;
    }

    public function setCircuit($circuit) {
        $this->circuit = $circuit;
    }

    public function getUnit() {
        return $this->circuit->getUnit();
    }
}