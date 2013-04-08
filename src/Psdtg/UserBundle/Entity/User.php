<?php
namespace Psdtg\UserBundle\Entity;

use FR3D\LdapBundle\Model\LdapUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Oh\GoogleMapFormTypeBundle\Validator\Constraints as OhAssert;
use JMS\SerializerBundle\Annotation\ExclusionPolicy;
use JMS\SerializerBundle\Annotation\Expose;
use JMS\SerializerBundle\Annotation\Accessor;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="Psdtg\UserBundle\Entity\Repositories\UserRepository")
 * @ExclusionPolicy("all")
 */
class User extends BaseUser implements LdapUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     *
     * @Assert\MinLength(limit="3", message="The name is too short.")
     * @Assert\MaxLength(limit="50", message="The name is too long.")
     * @Expose
     */
    protected $name;

    /**
     * Ldap Object Distinguished Name
     * @var string $dn
     */
    private $dn;

    /**
     * @ORM\OneToOne(targetEntity="Psdtg\SiteBundle\Entity\Unit")
     * @ORM\JoinColumn(name="mmId", referencedColumnName="mmId", onDelete="SET NULL")
     */
    protected $unit;

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

    public function getDn() {
        return $this->dn;
    }

    public function setDn($dn) {
        $this->dn = $dn;
    }

    public function getUnit() {
        return $this->unit;
    }

    public function setUnit($unit) {
        $this->unit = $unit;
    }

    public function void() {}
}