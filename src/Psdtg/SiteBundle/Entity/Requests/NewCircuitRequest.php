<?php

namespace Psdtg\SiteBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class NewCircuitRequest extends Request
{
    /**
     * @ORM\Column(name="circuitType", type="string", length=50)
     */
    protected $circuitType;

    public function getCircuitType() {
        return $this->circuitType;
    }

    public function setCircuitType($circuitType) {
        $this->circuitType = $circuitType;
    }

    public static function getNewCircuitRequestTypes() {
        return array(
            'pstn_dialup' => 'PSTN dialup',
            'pstn_adsl' => 'PSTN aDSL',
            'pstn_vdsl' => 'PSTN VDSL',
            'isdn_dialup' => 'ISDN dialup',
            'isdn_adsl' => 'ISDN aDSL',
            'isdn_vdsl' => 'ISDN VDSL',
            'vdsl_ll' => 'VDSL LL(LRE)',
            'll' => 'LL(M1020)',
            'eline_me' => 'e-line ME',
        );
    }
}