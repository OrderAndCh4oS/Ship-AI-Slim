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
 * @ORM\Entity(repositoryClass="Repository\ShipRepository")
 * @ORM\Table(name="photos", uniqueConstraints={@ORM\UniqueConstraint(name="photo_slug", columns={"slug"})}))
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
     * @ORM\Column(type="string", length="13")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $thursterPower;

    /**
     * @ORM\Column(type="integer")
     */
    private $turningSpeed;

    /**
     * @ORM\Column
     */

}