<?php namespace spec\Monolith\RelationalDatabase;

use Monolith\RelationalDatabase\CanNotExecuteQuery;
use Monolith\RelationalDatabase\Db;
use PhpSpec\ObjectBehavior;

class DbSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith('mysql:host=localhost;dbname=development', 'root', 'password');
        $this->write('drop table if exists example');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Db::class);
    }

    function it_can_query()
    {
        $create = 'create table example(example_id int);';
        $show = 'show tables;';
        $drop = 'drop table example;';

        $this->write($create);

        $result = $this->readOne($show);
        $result->Tables_in_development->shouldBe('example');

        $this->write($drop);

        $result = $this->readOne($show);
        $result->shouldBe(false);
    }

    function it_should_throw_on_queries_lacking_parameters()
    {
        $this->shouldThrow(CanNotExecuteQuery::class)->during('read', ['select * from :table']);
    }
}
