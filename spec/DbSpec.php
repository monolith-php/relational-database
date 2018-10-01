<?php namespace spec\Monolith\RelationalDatabase;

use Monolith\RelationalDatabase\CanNotExecuteQuery;
use Monolith\RelationalDatabase\CouldNotConnectWithPdo;
use Monolith\RelationalDatabase\Db;
use PhpSpec\ObjectBehavior;

class DbSpec extends ObjectBehavior
{
    function init() {
        $this->beConstructedWith('mysql:host=localhost;dbname=development', 'root', 'password');
        $this->write('drop table if exists example');
    }

    function it_is_initializable()
    {
        $this->init();
        $this->write('drop table if exists example');
        $this->shouldHaveType(Db::class);
    }

    function it_can_query()
    {
        $this->init();
        $create = 'create table example(example_id int);';
        $show = 'show tables;';
        $drop = 'drop table example;';

        $this->write($create);

        $result = $this->readFirst($show);
        $result->Tables_in_development->shouldBe('example');

        $this->write($drop);

        $result = $this->readFirst($show);
        $result->shouldBe(false);
    }

    function it_should_throw_on_queries_lacking_parameters()
    {
        $this->init();
        $this->shouldThrow(CanNotExecuteQuery::class)->during('readFirst', ['select * from :table']);
    }

    function it_throws_if_the_dsn_cant_be_parsed()
    {
        $this->beConstructedWith('unparsable', 'root', 'password');
        $this->shouldThrow(CouldNotConnectWithPdo::class)->duringInstantiation();
    }
}
