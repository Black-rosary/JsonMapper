<?php

namespace Yaroslav\JsonMapperBundle\Tests\Wrapper;

class JsonWrapperTest extends \PHPUnit_Framework_TestCase {

    protected function setUp() {
        parent::setUp();
    }

    /**
     * @expectedException \Yaroslav\JsonMapperBundle\HttpWrapper\Exception\JsonWrapperException
     */
    public function testJsonWrapperException() {
        $mock = $this->getMockBuilder('\Yaroslav\JsonMapperBundle\HttpWrapper\JsonWrapper')
                ->setMethods(['execute'])
                ->getMock();
        $mock->expects($this->once())->method('execute')->willReturn('{json error}');
        $mock->get('http://nowhere');
    }
    
    public function testDecode() {
        $mock = $this->getMockBuilder('\Yaroslav\JsonMapperBundle\HttpWrapper\JsonWrapper')
                ->setMethods(['execute'])
                ->getMock();
        $data = ['data'=>'test'];
        $mock->expects($this->once())->method('execute')->willReturn(json_encode($data));
        $return = $mock->get('http://nowhere');
        $this->assertEquals($return, $data);
    }

}
