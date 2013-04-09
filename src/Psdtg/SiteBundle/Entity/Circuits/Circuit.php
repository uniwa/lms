<?php

namespace Psdtg\SiteBundle\Entity\Circuits;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * @ORM\Table
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 * "phone_circuit" = "PhoneCircuit"
 * })
 */
abstract class Circuit
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Unit")
     * @ORM\JoinColumn(name="mmId", referencedColumnName="mmId", onDelete="SET NULL")
     */
    protected $unit;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     */
    protected $activatedAt;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUnit() {
        return $this->unit;
    }

    public function setUnit($unit) {
        $this->unit = $unit;
    }

    public function getActivatedAt() {
        return $this->activatedAt;
    }

    public function setActivatedAt($activatedAt) {
        $this->activatedAt = $activatedAt;
    }

    public function __toString() {
        if(isset($this->unit)) {
            return $this->unit->getName();
        } else {
            return 'Νέο Κύκλωμα';
        }
    }
}