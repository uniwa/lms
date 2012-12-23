<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class NewLineRequest extends Request
{
    /**
     * @ORM\Column(name="lineType", type="string", length=50)
     */
    protected $lineType = \Psdtg\SiteBundle\Entity\TelephoneLine::TYPE_PSTN;

    public function getLineType() {
        return $this->lineType;
    }

    public function setLineType($lineType) {
        $this->lineType = $lineType;
    }
}