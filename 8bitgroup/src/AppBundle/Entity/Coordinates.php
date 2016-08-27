<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coordinates
 *
 * @ORM\Table(name="coordinates")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CoordinatesRepository")
 */
class Coordinates
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float")
     */
    private $long;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float")
     */
    private $lat;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Coordinates
     */
    public function setLong($long)
    {
        $this->long = $long;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return Coordinates
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }
}

