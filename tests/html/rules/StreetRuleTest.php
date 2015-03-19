<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 */
/**
*
*/
class StreetRuleTest extends PHPUnit_Framework_TestCase {
    
    /**
	 * @test
	 **/
    public function setUp() {
        $this->o = new StreetRule();
    }
    
    /**
	 * @test
	 **/
    public function validAddress(){
        $addresses = array("123 Some Street", "3134 Derp Street & Highway 18");
        foreach ($addresses as $address) {
            $this->o->value = $address;
            $this->assertTrue($this->o->run());
        }
    }
    
    /**
	 * @test
	 **/
    public function invalidAddress(){
        $addresses = array("<script>alert('bacon')</script>", ";");
        foreach ($addresses as $address) {
            $this->o->value = $address;
            $this->assertFalse($this->o->run());
        }
    }
}
