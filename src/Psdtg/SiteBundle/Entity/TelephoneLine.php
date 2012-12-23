<?php

namespace Cookisto\SiteBundle\Entity;

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
class Dish
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
    protected $ypepth_id;

    /**
     * @ORM\Column(name="number", type="string", length=16)
     * @Expose
     */
    protected $number;

    /**
     * @ORM\Column(name="status", type="string", length=100)
     * @Expose
     */
    protected $status;

    /**
     * @ORM\Column(name="installDate", type="datetime")
     * @Expose
     */
    protected $installDate;

    // OneToOne
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
    protected $lineType;

    const TYPE_PSTN = 'pstn';
    const TYPE_ISDN = 'isdn';

    // OneToMany
    protected $adsl;
}