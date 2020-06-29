<?php namespace Monolith\RelationalDatabase;

use Monolith\Configuration\Config;

final class RelationalDatabaseBootstrap implements \Monolith\ComponentBootstrapping\ComponentBootstrap
{
    public function bind(\Monolith\DependencyInjection\Container $container): void
    {
        $container->bind(Db::class, function ($r) {
            return new Db(
                $r(Config::class)->get('RELATIONAL_DATABASE_DSN'),
                $r(Config::class)->get('RELATIONAL_DATABASE_USERNAME'),
                $r(Config::class)->get('RELATIONAL_DATABASE_PASSWORD')
            );
        });
    }

    public function init(\Monolith\DependencyInjection\Container $container): void
    {

    }
}
