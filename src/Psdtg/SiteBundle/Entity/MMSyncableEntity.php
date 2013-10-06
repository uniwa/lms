<?php
namespace Psdtg\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

/** @MappedSuperclass */
abstract class MMSyncableEntity
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $mmSyncId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $mmSyncLastUpdateDate;

    public function getMmSyncId() {
        return $this->mmSyncId;
    }

    public function setMmSyncId($mmSyncId) {
        $this->mmSyncId = $mmSyncId;
    }

    public function getMmSyncLastUpdateDate() {
        return $this->mmSyncLastUpdateDate;
    }

    public function setMmSyncLastUpdateDate($mmSyncLastUpdateDate) {
        $this->mmSyncLastUpdateDate = $mmSyncLastUpdateDate;
    }

    public abstract function isActive();
}