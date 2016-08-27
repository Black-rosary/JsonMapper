<?php

namespace AppBundle\Entity;

class LocationCollection
{

    /**
     *
     * @var array 
     */
    private $locations;

    public function getLocations() {
        return $this->locations;
    }

    public function setLocations(array $locations) {
        $this->locations = $locations;
        return $this;
    }



}

