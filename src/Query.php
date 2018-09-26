<?php namespace Monolith\RelationalDatabase;

use PDO;

final class Query {

    /** @var PDO */
    private $pdo;

    public function __construct($dsn, $username, $password) {
        $this->pdo = new PDO($dsn, $username, $password);
    }

    public function execute($query, array $placeholders = []) {
        $statement = $this->pdo->prepare($query);
        $statement->execute($placeholders);
        return $statement->fetch();
    }
}