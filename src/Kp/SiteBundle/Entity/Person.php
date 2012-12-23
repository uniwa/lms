<?php

namespace Kp\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Person extends BasePage
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $photoPath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $hidefPhotoPath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $hidefPhoto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $vcardPath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $vcard;

    /**
     * @ORM\Column(name="lastfileupdate", type="datetime")
     */
    protected $lastfileupdate;

    /**
     * @ORM\Column(name="name", type="string")
     */
    protected $name;
    /**
     * @ORM\Column(name="surname", type="string")
     */
    protected $surname;
    /**
     * @ORM\Column(name="title", type="string")
     */
    protected $title;
    /**
     * @ORM\Column(name="position", type="string")
     */
    protected $position;
    /**
     * @ORM\Column(name="phone", type="string")
     */
    protected $phone;
    /**
     * @ORM\Column(name="fax", type="string")
     */
    protected $fax;
    /**
     * @ORM\Column(name="email", type="string")
     */
    protected $email;
    /**
     * @ORM\Column(name="lync", type="string")
     */
    protected $lync;
    /**
     * @ORM\Column(name="languages", type="string")
     */
    protected $languages;
    /**
     * @ORM\Column(name="education", type="string")
     */
    protected $education;

    public function __construct() {
        $this->authorIn = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lastfileupdate = new \DateTime();
        parent::__construct();
    }

    public function getType() {
        return 'person';
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getFax() {
        return $this->fax;
    }

    public function setFax($fax) {
        $this->fax = $fax;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getLync() {
        return $this->lync;
    }

    public function setLync($lync) {
        $this->lync = $lync;
    }

    public function getLanguages() {
        return $this->languages;
    }

    public function setLanguages($languages) {
        $this->languages = $languages;
    }

    public function getEducation() {
        return $this->education;
    }

    public function setEducation($education) {
        $this->education = $education;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function setPhoto($photo) {
        $this->lastfileupdate = new \DateTime();
        $this->photo = $photo;
    }

    public function getHidefPhoto() {
        $this->lastfileupdate = new \DateTime();
        return $this->hidefPhoto;
    }

    public function setHidefPhoto($hidefPhoto) {
        $this->hidefPhoto = $hidefPhoto;
    }

    public function getVcard() {
        return $this->vcard;
    }

    public function setVcard($vcard) {
        $this->lastfileupdate = new \DateTime();
        $this->vcard = $vcard;
    }

    public function getCaption() {
        return $this->title;
    }

    public function getPhotoAbsolutePath()
    {
        return null === $this->photoPath ? null : $this->getUploadRootDir().'/'.$this->photoPath;
    }

    public function getPhotoWebPath()
    {
        return null === $this->photoPath ? null : $this->getUploadDir().'/'.$this->photoPath;
    }

    public function getHidefPhotoAbsolutePath()
    {
        return null === $this->hidefPhotoPath ? null : $this->getUploadRootDir().'/'.$this->hidefPhotoPath;
    }

    public function getHidefPhotoWebPath()
    {
        return null === $this->hidefPhotoPath ? null : $this->getUploadDir().'/'.$this->hidefPhotoPath;
    }

    public function getVcardAbsolutePath()
    {
        return null === $this->vcardPath ? null : $this->getUploadRootDir().'/'.$this->vcardPath;
    }

    public function getVcardWebPath()
    {
        return null === $this->vcardPath ? null : $this->getUploadDir().'/'.$this->vcardPath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'upload/people';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->photo) {
            $this->removePhotoUpload();
            // do whatever you want to generate a unique name
            $this->photoPath = $this->getId().'_photo.'.$this->photo->guessExtension();
        }
        if (null !== $this->hidefPhoto) {
            $this->removeHidefPhotoUpload();
            // do whatever you want to generate a unique name
            $this->hidefPhotoPath = $this->getId().'_hidefphoto.'.$this->hidefPhoto->guessExtension();
        }
        if (null !== $this->vcard) {
            $this->removeVcardUpload();
            // do whatever you want to generate a unique name
            $this->vcardPath = $this->getId().'_vcard.'.$this->vcard->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null !== $this->photo) {
            // if there is an error when moving the file, an exception will
            // be automatically thrown by move(). This will properly prevent
            // the entity from being persisted to the database on error
            $this->photo->move($this->getUploadRootDir(), $this->photoPath);
            unset($this->photo);
        }
        if (null !== $this->hidefPhoto) {
            // if there is an error when moving the file, an exception will
            // be automatically thrown by move(). This will properly prevent
            // the entity from being persisted to the database on error
            $this->hidefPhoto->move($this->getUploadRootDir(), $this->hidefPhotoPath);
            unset($this->hidefPhoto);
        }
        if (null !== $this->vcard) {
            // if there is an error when moving the file, an exception will
            // be automatically thrown by move(). This will properly prevent
            // the entity from being persisted to the database on error
            $this->vcard->move($this->getUploadRootDir(), $this->vcardPath);
            unset($this->vcard);
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removePhotoUpload()
    {
        $file = $this->getPhotoAbsolutePath();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeHidefPhotoUpload()
    {
        $file = $this->getHidefPhotoAbsolutePath();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeVcardUpload()
    {
        $file = $this->getVcardAbsolutePath();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function getFullName() {
        return $this->name.' '.$this->surname;
    }

    public function getShortTitle() {
        return $this->getSurname();
    }

    public function getFullCaption() {
        return $this->getTitle().' '.$this->getPosition();
    }

    public function __toString() {
        return $this->getFullName();
    }
}