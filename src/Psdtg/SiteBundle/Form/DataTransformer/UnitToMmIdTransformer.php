<?php

namespace Psdtg\SiteBundle\Form\DataTransformer;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use Psdtg\SiteBundle\Entity\Unit;
use Psdtg\SiteBundle\Extension\MMService;

/**
 * Transforms between a UserInterface instance and a username string.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class UnitToMmIdTransformer implements DataTransformerInterface
{
    protected $mmservice;

    public function __construct(MMService $mmservice)
    {
        $this->mmservice = $mmservice;
    }

    /**
     * Transforms a Unit instance into an mmId string.
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        /*if ($value instanceof Unit) {
            return $value->getMmId();
        } else if (is_int($value)) {
            return $this->mmservice->find($value);
        } else {
            throw new UnexpectedTypeException($value, 'Psdtg\SiteBundle\Entity\Unit');
        }*/
        return $value;
    }

    /**
     * Transforms an mmId string into a Unit instance.
     */
    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof Unit) {
            return $this->mmservice->find($value->getMmId());
        } else if (is_string($value)) {
            return $this->mmservice->find($value);
        } else {
            throw new UnexpectedTypeException($value, 'string');
        }
    }
}
