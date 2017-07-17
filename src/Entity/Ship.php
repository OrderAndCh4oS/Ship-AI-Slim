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
 * @ORM\Entity(repositoryClass="Oacc\Repository\ShipRepository")
 * @ORM\Table(name="ship")
 */
class Ship
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Oacc\Entity\Team", inversedBy="ships")
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=13)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $thrusterPower;

    /**
     * @ORM\Column(type="integer")
     */
    private $turningSpeed;

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
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
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
}