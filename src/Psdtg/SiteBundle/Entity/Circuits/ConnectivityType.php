<?php

namespace Psdtg\SiteBundle\Entity\Circuits;

use Psdtg\SiteBundle\Entity\MMSyncableEntity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Psdtg\SiteBundle\Entity\Repositories\Circuits\ConnectivityTypesRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class ConnectivityType extends MMSyncableEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $noLease = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isService = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $requiresNumber = false;

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

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getNoLease() {
        return $this->noLease;
    }

    public function setNoLease($noLease) {
        $this->noLease = $noLease;
    }

    public function getIsService() {
        return $this->isService;
    }

    public function setIsService($isService) {
        $this->isService = $isService;
    }

    public function getRequiresNumber() {
        return $this->requiresNumber;
    }

    public function setRequiresNumber($requiresNumber) {
        $this->requiresNumber = $requiresNumber;
    }

    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }

    public function isActive() {
        return !isset($this->deletedAt);
    }

    public function __toString() {
        return $this->getName();
    }
}