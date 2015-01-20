<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NormalIndexFactoryMySQLDriverSpec extends ObjectBehavior
{
    function let() {
        $MySqlSettings = array(
            'server' => 'localhost',
            'username' => 'site',
            'password' => 'ajnin_edoc_12',
            'database' => 'igdata'
        );
        $this->beConstructedWith($MySqlSettings);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('NormalIndexFactoryMySQLDriver');
    }

    function it_should_implement_driver_interface() {
        $this->shouldImplement('NormalIndexFactoryDriverInterface');
    }

    function it_should_throw_an_exception() {
        $this->shouldThrow('\Exception')->during('throwError', array(404, 'Page not found'));
    }

    function it_should_connect_to_host() {
        $this->connect()->shouldBeBool();
    }

    function it_should_accept_table_name() {
        $this->setTable('normal_index')->shouldReturnAnInstanceOf('NormalIndexFactoryMySQLDriver');
    }

    function it_should_set_limit() {
        $this->setLimit(1)->shouldReturnAnInstanceOf('NormalIndexFactoryMySQLDriver');
    }

    function it_should_return_array_of_records() {
        $this->connect();
        $this->setLimit(1)
            ->setTable('normal_index')
            ->getRecords()
            ->shouldBeArray();
    }


    function it_should_disconnect_from_host() {
        $this->connect();
        $this->disconnect()->shouldBeBool();
    }
}
