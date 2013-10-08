<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class RemoveCircuitRequest extends Request
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
}