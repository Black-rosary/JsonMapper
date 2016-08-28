<?php

namespace Yaroslav\JsonMapperBundle\Tests\Wrapper;

class CurlWrapperTest extends \PHPUnit_Framework_TestCase {

    protected function setUp() {
        parent::setUp();
    }

    public function testGet() {
        $mock = $this->getMockBuilder('\Yaroslav\JsonMapperBundle\HttpWrapper\CurlWrapper')
                ->setMethods(array('execute'))
                ->getMock();
        $mock->expects($this->once())->method('execute')->willReturn('test');
        $return = $mock->get('http://nowhere');
        $this->assertEquals($return, 'test');
    }
    
    
    public function testPost() {
        $mock = $this->getMockBuilder('\Yaroslav\JsonMapperBundle\HttpWrapper\CurlWrapper')
                ->setMethods(array('execute'))
                ->getMock();
        $mock->expects($this->once())->method('execute')->willReturn('test');
        $return = $mock->post('http://nowhere');
        $this->assertEquals($return, 'test');
    }

}
