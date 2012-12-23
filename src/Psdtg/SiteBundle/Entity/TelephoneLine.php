<?php

namespace Psdtg\SiteBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\SerializerBundle\Annotation\ExclusionPolicy;
use JMS\SerializerBundle\Annotation\Expose;
use JMS\SerializerBundle\Annotation\AccessType;
use JMS\SerializerBundle\Annotation\Accessor;
use JMS\SerializerBundle\Annotation\ReadOnly;

/**
 * Cookisto\SiteBundle\Entity\Dish
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @AccessType("public_method")
 */
class TelephoneLine
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @ORM\Column(name="ypepth_id", type="string", length=100)
     * @Expose
     */
    protected $ypepthId;

    /**
     * @ORM\Column(name="number", type="string", length=16, nullable=true)
     * @Expose
     */
    protected $number;

    /**
     * @ORM\Column(name="status", type="string", length=100)
     * @Expose
     */
    protected $status = self::STATUS_PENDING;

    const STATUS_PENDING = 'PENDING';
    const STATUS_INSTALLED = 'INSTALLED';

    /**
     * @ORM\Column(name="installDate", type="datetime", nullable=true)
     * @Expose
     */
    protected $installDate;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Requests\NewLineRequest", mappedBy="line")
     */
    protected $newLineRequest;

    /**
     * @ORM\Column(name="address", type="string")
     * @Expose
     */
    protected $address;

    /**
     * @ORM\Column(name="lineType", type="string", length=50)
     * @Expose
     */
    protected $lineType = self::TYPE_PSTN;

    const TYPE_PSTN = 'pstn';
    const TYPE_ISDN = 'isdn';

    // OneToMany
    protected $adsl;

    /**
     * @ORM\Column(name="creationdate", type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Expose
     */
    protected $creationdate;

    /**
     * @ORM\Column(name="lastupdatedate", type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @Expose
     */
    protected $lastupdatedate;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getYpepthId() {
        return $this->ypepthId;
    }

    public function setYpepthId($ypepthId) {
        $this->ypepthId = $ypepthId;
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public static function getStatuses() {
        return array(
            self::STATUS_PENDING => self::STATUS_PENDING,
            self::STATUS_INSTALLED => self::STATUS_INSTALLED,
        );
    }

    public function getInstallDate() {
        return $this->installDate;
    }

    public function setInstallDate($installDate) {
        $this->installDate = $installDate;
    }

    public function getNewLineRequest() {
        return $this->newLineRequest;
    }

    public function setNewLineRequest($newLineRequest) {
        $this->newLineRequest = $newLineRequest;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getLineType() {
        return $this->lineType;
    }

    public function setLineType($lineType) {
        $this->lineType = $lineType;
    }

    public static function getLineTypes() {
        return array(
            self::TYPE_PSTN => self::TYPE_PSTN,
            self::TYPE_ISDN => self::TYPE_ISDN,
        );
    }

    public function getAdsl() {
        return $this->adsl;
    }

    public function setAdsl($adsl) {
        $this->adsl = $adsl;
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
}