<?php


namespace Psdtg\SiteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ReadOnly;

/**
 * @ExclusionPolicy("all")
 * @AccessType("public_method")
 */
class UnitFy {
    /**
     * @Expose
     */
    protected $name;
    /**
     * @Expose
     */
    protected $initials;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getInitials() {
        return $this->initials;
    }

    public function setInitials($initials) {
        $this->initials = $initials;
    }

    public function __toString() {
        return $this->getName();
    }
}

?>
