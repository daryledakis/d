<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


use NormalIndexFactoryMySQLDriver;

class NormalIndexFactorySpec extends ObjectBehavior
{
    function let() {
        $MySqlSettings = array(
            'server' => 'localhost',
            'username' => 'site',
            'password' => 'ajnin_edoc_12',
            'database' => 'igdata'
        );
        $MySqlDriver = new NormalIndexFactoryMySQLDriver($MySqlSettings);

        $this->beConstructedWith($MySqlDriver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('NormalIndexFactory');
    }

    function it_should_accept_factory_driver() {
        $MySqlSettings = array(
            'server' => 'localhost',
            'username' => 'site',
            'password' => 'ajnin_edoc_12',
            'database' => 'igdata'
        );
        $MySqlDriver = new NormalIndexFactoryMySQLDriver($MySqlSettings);

        $this->beConstructedWith($MySqlDriver);
        $this->setDriver($MySqlDriver)->shouldReturnAnInstanceOf('NormalIndexFactory');
    }


    function it_should_accept_table_name() {
        $this->setTableName('normal_index')->shouldReturnAnInstanceOf('NormalIndexFactory');
    }

    function it_should_connect_to_host() {
        $this->connect()->shouldBeBool();
    }

    function it_should_set_limit() {
        $this->connect();
        $this->setLimit(1)->shouldReturnAnInstanceOf('NormalIndexFactory');
    }

    function it_should_return_array_of_records() {
        $this->connect();
        $this->setTableName('normal_index')
            ->setLimit(1)
            ->getRecords()
            ->shouldBeArray();
    }

    function it_should_disconnect_from_host() {
        $this->connect();
        $this->disconnect()->shouldBeBool();
    }
}
