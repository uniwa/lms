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
 * @ORM\DiscriminatorMap({
 *  "new_circuit" = "NewCircuitRequest",
 *  "remove_circuit" = "RemoveCircuitRequest",
 *  "activate_service" = "ActivateServiceRequest",
 *  "change_service" = "ChangeConnectivityTypeRequest",
 *  "change_ownership" = "ChangeOwnershipRequest",
 * })
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
     * @Gedmo\Versioned
     * @ORM\Column(name="status", type="string", length=100)
     */
    protected $status = self::STATUS_PENDING;

    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $comments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $activatedAt;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getComments() {
        return $this->comments;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }

    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }

    public function getActivatedAt() {
        return $this->activatedAt;
    }

    public function setActivatedAt($activatedAt) {
        $this->activatedAt = $activatedAt;
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
        if($this->getId() != null) {
            return (string)'Αίτηση '.$this->getId();
        } else {
            return 'Νέα Αίτηση';
        }
    }
}