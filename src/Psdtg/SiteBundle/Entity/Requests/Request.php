<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"new_circuit" = "NewCircuitRequest"})
 */
class Request
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Circuit")
     * @ORM\JoinColumn(name="circuitId", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $line;

    /**
     * @ORM\Column(name="ypepth_id", type="string", length=100)
     */
    protected $ypepthId;

    /**
     * @ORM\Column(name="submitter_id", type="string", length=100)
     */
    protected $submitterId;

    /**
     * @ORM\Column(name="tech_factsheet_no", type="string", length=100)
     */
    protected $techFactsheetNo;

    /**
     * @ORM\Column(name="creationdate", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $creationdate;

    /**
     * @ORM\Column(name="lastupdatedate", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $lastupdatedate;

    /**
     * @ORM\Column(name="status", type="string", length=100)
     */
    protected $status = self::STATUS_PENDING;

    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';

    /**
     * @ORM\Column(name="laststatusupdatedate", type="datetime")
     */
    protected $laststatusupdatedate;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLine() {
        return $this->line;
    }

    public function setLine($line) {
        $this->line = $line;
    }

    public function getYpepthId() {
        return $this->ypepthId;
    }

    public function setYpepthId($ypepthId) {
        $this->ypepthId = $ypepthId;
    }

    public function getSubmitterId() {
        return $this->submitterId;
    }

    public function setSubmitterId($submitterId) {
        $this->submitterId = $submitterId;
    }

    public function getTechFactsheetNo() {
        return $this->techFactsheetNo;
    }

    public function setTechFactsheetNo($techFactsheetNo) {
        $this->techFactsheetNo = $techFactsheetNo;
    }

    public function getCreationdate() {
        return $this->creationdate;
    }

    public function setCreationdate($creationdate) {
        $this->creationdate = $creationdate;
    }

    public function getLastupdatedate() {
        return $this->lastupdatedate;
    }

    public function setLastupdatedate($lastupdatedate) {
        $this->lastupdatedate = $lastupdatedate;
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

    public function getLaststatusupdatedate() {
        return $this->laststatusupdatedate;
    }

    public function setLaststatusupdatedate($laststatusupdatedate) {
        $this->laststatusupdatedate = $laststatusupdatedate;
    }
}