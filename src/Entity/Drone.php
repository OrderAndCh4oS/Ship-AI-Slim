<?php
/**
 * Created by PhpStorm.
 * User: HP-DEV3
 * Date: 14/07/2017
 * Time: 14:07
 */

namespace Oacc\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Oacc\Repository\DroneRepository")
 * @ORM\Table(name="drone")
 */
class Drone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=13)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $thrusterPower = 5;

    /**
     * @ORM\Column(type="integer")
     */
    private $turningSpeed = 5;

    /**
     * @ORM\Column(type="integer")
     */
    private $kills = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Oacc\Entity\Squadron", inversedBy="drones")
     */
    private $squadron;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSquadron()
    {
        return $this->squadron;
    }

    /**
     * @param mixed $squadron
     */
    public function setSquadron($squadron)
    {
        $this->squadron = $squadron;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getThrusterPower()
    {
        return $this->thrusterPower;
    }

    /**
     * @param mixed $thrusterPower
     */
    public function setThrusterPower($thrusterPower)
    {
        $this->thrusterPower = $thrusterPower;
    }

    /**
     * @return mixed
     */
    public function getTurningSpeed()
    {
        return $this->turningSpeed;
    }

    /**
     * @param mixed $turningSpeed
     */
    public function setTurningSpeed($turningSpeed)
    {
        $this->turningSpeed = $turningSpeed;
    }

    /**
     * @return int
     */
    public function getKills()
    {
        return $this->kills;
    }

    /**
     * @param int $kills
     */
    public function setKills($kills)
    {
        $this->kills = $kills;
    }

    /**
     * @param $newStatValue
     * @param $currentStatValue
     * @return bool
     */
    public function isStatIncrease($newStatValue, $currentStatValue)
    {
        $difference = $newStatValue - $currentStatValue;

        return $difference > 0;
    }

    /**
     * @param $difference
     * @return mixed
     */
    public function canAffordStatIncrease($difference)
    {
        return $difference <= $this->getSquadron()->getCash();
    }
}