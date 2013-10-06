<?php

namespace Psdtg\SiteBundle\Entity\Circuits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Psdtg\SiteBundle\Entity\Repositories\Circuits\ConnectivityTypesRepository")
 */
class ConnectivityType
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

    public function __toString() {
        return $this->getName();
    }
}