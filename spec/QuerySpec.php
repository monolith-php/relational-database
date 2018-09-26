<?php namespace spec\Monolith\RelationalDatabase;

use Monolith\RelationalDatabase\Query;
use PhpSpec\ObjectBehavior;

class QuerySpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith('mysql:host=localhost;dbname=development', 'root', 'password');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Query::class);
    }

    function it_can_query()
    {
        $create = 'create table example(example_id int);';
        $show = 'show tables;';
        $drop = 'drop table example;';

        $this->execute($create);

        $result = $this->execute($show);
        $result[0]->shouldBe('example');

        $this->execute($drop);
        
        $result = $this->execute($show);
        $result->shouldBe(false);
    }
}
