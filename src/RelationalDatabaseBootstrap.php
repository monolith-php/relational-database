<?php Monolith\RelationalDatabase;

class RelationalDatabaseBootstrap implements \Monolith\ComponentLoading\ComponentBootstrap {

    public function bind(\Monolith\DependencyInjection\Container $container): void {
        $container->bind(PDO::class, function() {
            return new PDO(get_env('RELATIONAL_DATABASE_DSN'));
        });
    }

    public function init(\Monolith\DependencyInjection\Container $container): void {

    }
}