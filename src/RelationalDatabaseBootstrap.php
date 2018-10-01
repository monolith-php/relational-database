<?php use Monolith\RelationalDatabase\Db;

Monolith\RelationalDatabase;

final class RelationalDatabaseBootstrap implements \Monolith\ComponentBootstrapping\ComponentBootstrap
{
    public function bind(\Monolith\DependencyInjection\Container $container): void
    {
        $container->bind(Db::class, function () {
            return new Db(get_env('RELATIONAL_DATABASE_DSN'));
        });
    }

    public function init(\Monolith\DependencyInjection\Container $container): void
    {

    }
}
