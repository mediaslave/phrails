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
        $emails = array('cardoso.ro@ig.com.br',
            'grosulgennadii@rambler.ru',
            'enrollments@worldstudy.com.br',
            'sylvain.loup@hotmail.com',
            'rafaelrivas.ve@hotmail.com',
            'v.cruchet@hotmail.com',
            'dile92@hotmail.com'
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
