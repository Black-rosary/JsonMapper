<?php

namespace Yaroslav\JsonMapperBundle\Tests\Mapper;

use Yaroslav\JsonMapperBundle\Tests\TestKernel;

class JsonMapperTest extends \PHPUnit_Framework_TestCase {

    protected $mapper;

    protected function setUp()
    {
        parent::setUp();
        $kernel = new TestKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $this->mapper = $container->get('yaroslav_json_mapper_test1');
    }
    
    public function testMapper() {
        $data = $this->mapper->findAll();
        $this->assertInstanceOf('AppBundle\Entity\LocationCollection', $data);
        $this->assertCount(2, $data->getLocations());
        $locations = $data->getLocations();
        $location = $locations[0];
        $this->assertInstanceOf('AppBundle\Entity\Location', $location);
        $this->assertInstanceOf('AppBundle\Entity\Coordinates', $location->getCoordinates());
        $coordinates = $location->getCoordinates();
        $this->assertEquals($coordinates->getLong(), '19.56');
    }
    
    
}    