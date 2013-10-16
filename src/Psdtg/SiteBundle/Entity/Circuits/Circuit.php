<?php

namespace Psdtg\SiteBundle\Entity\Circuits;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;
use Psdtg\SiteBundle\Entity\MMSyncableEntity;

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
 * @ORM\Entity(repositoryClass="Psdtg\SiteBundle\Entity\Repositories\Circuits\CircuitsRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 * "phone_circuit" = "PhoneCircuit"
 * })
 * @ExclusionPolicy("all")
 */
abstract class Circuit extends MMSyncableEntity
{
    use TimestampableEntity;
    use BlameableEntity;

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
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Requests\NewCircuitRequest", inversedBy="circuit")
     * @ORM\JoinColumn(name="newCircuitRrequestId", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $newCircuitRequest;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     */
    protected $activatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     */
    protected $deletedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Expose
     */
    protected $comments;

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

    public function getNewCircuitRequest() {
        return $this->newCircuitRequest;
    }

    public function setNewCircuitRequest($newCircuitRequest) {
        $this->newCircuitRequest = $newCircuitRequest;
    }

    public function getActivatedAt() {
        return $this->activatedAt;
    }

    public function setActivatedAt(\DateTime $activatedAt = null) {
        $this->activatedAt = $activatedAt;
    }

    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }

    public function getComments() {
        return $this->comments;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }

    public function isActive() {
        return !isset($this->deletedAt);
    }

    public function __toString() {
        if(isset($this->unit)) {
            return $this->unit->getName();
        } else {
            return 'Νέο Κύκλωμα';
        }
    }
}