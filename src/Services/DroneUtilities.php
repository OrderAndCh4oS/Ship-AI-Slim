<?php
/**
 * Created by PhpStorm.
 * User: HP-DEV3
 * Date: 04/09/2017
 * Time: 13:40
 */

namespace Oacc\Services;

use Doctrine\ORM\EntityManager;
use Oacc\Entity\Squadron;

class DroneUtilities
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var int
     */
    private $statChangeCost;

    /**
     * DroneUtilities constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $new
     * @param $current
     */
    public function updateStatChangeCost($new, $current)
    {
        $difference = $new - $current;
        if ($difference > 0) {
            $this->statChangeCost += $difference;
        }
    }

    /**
     * @param $squadron
     * @throws \Exception
     */
    public function spendCash(Squadron $squadron)
    {
        if ($this->statChangeCost <= $squadron->getCash()) {
            $cash = $squadron->getCash() - $this->statChangeCost;
            $squadron->setCash($cash);
            $this->em->persist($squadron);
        } else {
            throw new \Exception("Not enough cash");
        }
    }

    /**
     * @return int
     */
    public function getStatChangeCost()
    {
        return $this->statChangeCost;
    }
}