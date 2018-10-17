<?php namespace spec\Monolith\RelationalDatabase;

use Monolith\RelationalDatabase\CanNotExecuteQuery;
use Monolith\RelationalDatabase\CouldNotConnectWithPdo;
use Monolith\RelationalDatabase\Db;
use PhpSpec\ObjectBehavior;

class DbSpec extends ObjectBehavior
{
    function init()
    {
        $this->beConstructedWith('mysql:host=localhost;dbname=development', 'root', 'password');
        $this->write('drop table if exists example');
        $this->createTable();
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

        $this->writeToTable(1);
        $this->verifyIntExists(1);
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

    function it_can_commit_transactions()
    {
        $this->init();

        $this->beginTransaction();

        $this->writeToTable(1);
        $this->verifyIntExists(1);

        $this->commitTransaction();

        $this->verifyIntExists(1);
    }

    function it_can_roll_back_commits()
    {
        $this->init();

        $this->beginTransaction();

        $this->writeToTable(1);
        $this->verifyIntExists(1);

        $this->rollbackTransaction();

        $this->verifyIntDoesntExist(1);
    }

    private function createTable(): void
    {
        $this->write('create table example(example_id int);');
    }

    private function writeToTable(int $int): void
    {
        $this->write('insert into example (example_id) values (:int)', [
            'int' => $int,
        ]);
    }

    private function verifyIntExists(int $int): void
    {
        $result = $this->readFirst('select * from example where example_id = :int', [
            'int' => $int
        ]);
        $result->example_id->shouldBe((string) $int);
    }

    private function verifyIntDoesntExist(int $int): void
    {
        $result = $this->readFirst('select * from example where example_id = :int', [
            'int' => $int
        ]);

        $result->shouldBe(false);
    }
}
