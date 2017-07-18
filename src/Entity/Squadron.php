<?php
/**
 * Created by PhpStorm.
 * User: sarcoma
 * Date: 17/07/17
 * Time: 08:01
 */

namespace Oacc\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Oacc\Repository\SquadronRepository")
 * @ORM\Table(name="squadron")
 */
class Squadron
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $cash = 100;

    /**
     * @ORM\OneToMany(targetEntity="Oacc\Entity\Drone", mappedBy="squadron", orphanRemoval=true)
     */
    private $drones;

    public function __construct()
    {
        $this->drones = new ArrayCollection();
    }

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
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @param mixed $cash
     */
    public function setCash($cash)
    {
        $this->cash = $cash;
    }

    /**
     * @return mixed
     */
    public function getDrones()
    {
        return $this->drones;
    }

    /**
     * @param mixed $drones
     */
    public function setDrones($drones)
    {
        $this->drones = $drones;
    }
}