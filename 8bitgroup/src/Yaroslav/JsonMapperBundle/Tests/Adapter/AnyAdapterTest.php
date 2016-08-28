<?php

namespace Yaroslav\JsonMapperBundle\Tests\Adapter;

class AnyAdapterTest extends \PHPUnit_Framework_TestCase {

    protected function setUp() {
        parent::setUp();
    }

    /**
     * @expectedException \Yaroslav\JsonMapperBundle\Adapter\Exception\AdapterException
     */
    public function testAnyAdapterException() {
        $mock = $this->getMockBuilder('\Yaroslav\JsonMapperBundle\Adapter\AnyAdapter')
                ->setMethods(['execute'])
                ->getMock();
        
        $data = ['data'=> [
            'message' => 'No future',
            'code' => 100
        ], 'status' => false];
        
        $mock->expects($this->once())->method('execute')->willReturn(json_encode($data));
        $mock->find('http://test');
    }
    
    /**
     * @expectedException \Yaroslav\JsonMapperBundle\Adapter\Exception\AdapterException
     */
    public function testAnyAdapterException2() {
        $mock = $this->getMockBuilder('\Yaroslav\JsonMapperBundle\Adapter\AnyAdapter')
                ->setMethods(['execute'])
                ->getMock();
        
        $data = ['nodata'=> [
        ], 'status' => false];
        
        $mock->expects($this->once())->method('execute')->willReturn(json_encode($data));
        $mock->find('http://test');
    }
    
    public function testGet() {
        $mock = $this->getMockBuilder('\Yaroslav\JsonMapperBundle\Adapter\AnyAdapter')
                ->setMethods(['execute'])
                ->getMock();
        $data = ['data'=> [
                'test'=>'test'],
        'status' => true];
        $mock->expects($this->once())->method('execute')->willReturn(json_encode($data));
        $return = $mock->find('http://nowhere');
        $this->assertEquals($return, $data);
    }

}
