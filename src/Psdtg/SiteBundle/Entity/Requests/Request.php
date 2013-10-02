<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"new_circuit" = "NewCircuitRequest"})
 * @Gedmo\Loggable
 */
class Request
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuits\Circuit")
     * @ORM\JoinColumn(name="circuitId", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $circuit;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Unit")
     * @ORM\JoinColumn(name="mmId", referencedColumnName="mmId", onDelete="SET NULL")
     */
    protected $unit;

    /**
     * @ORM\Column(name="tech_factsheet_no", type="string", length=100)
     */
    protected $techFactsheetNo;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(name="status", type="string", length=100)
     */
    protected $status = self::STATUS_PENDING;

    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCircuit() {
        return $this->circuit;
    }

    public function setCircuit($circuit) {
        $this->circuit = $circuit;
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

    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        $this->laststatusupdatedate = new \DateTime('now');
    }

    public static function getStatuses() {
        return array(
            self::STATUS_PENDING => self::STATUS_PENDING,
            self::STATUS_APPROVED => self::STATUS_APPROVED,
            self::STATUS_REJECTED => self::STATUS_REJECTED,
        );
    }

    public function __toString() {
        return (string)'Αίτηση '.$this->getTechFactsheetNo();
    }
}