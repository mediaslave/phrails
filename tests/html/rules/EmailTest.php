<?php

/**
 * 
 */
class EmailTest extends PHPUnit_Framework_TestCase {

    /**
     * undocumented function
     *
     * @return void
     * @author Shaun Gilliland
     * */
    public function setUp() {
        $this->o = new EmailRule();
    }

    /**
     * @test
     * */
    public function valid_emails() {
        $emails = array('bobert@bobby.com',
            'bobert@192.168.0.1',
            'bobby_bobert@email.com',
            'bobby-bobert@email.com',
            'Bobby-BoBert@email.com',
            'Bobby_Bobert@email.com',
            'Bobert@192.168.240.25'
        );
        foreach ($emails as $email) {
            $this->o->value = $email;
            $this->assertTrue($this->o->run());
        }
    }

    /**
     * @test
     * */
    public function invalid_emails() {
        $emails = array('Bobert@192.emai!.com','asdfqaweoianv ','@#$@#@@ghiDI nasdi.EmAiL.CoM');
        foreach ($emails as $email) {
            $this->o->value = $email;
            $this->assertFalse($this->o->run());
        }
    }

}
