<?php namespace Monolith\RelationalDatabase;

use PDO;

class Db
{
    /** @var PDO */
    private $pdo;

    public function __construct($dsn, $username, $password)
    {
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function write($query, array $placeholders = []): void
    {
        $statement = $this->pdo->prepare($query);

        try {
            $statement->execute($placeholders);
        } catch (\PDOException $e) {
            throw new CanNotExecuteQuery($e->getMessage());
        }
    }

    public function readFirst($query, array $placeholders = [])
    {
        $statement = $this->pdo->prepare($query);

        try {
            $statement->execute($placeholders);
        } catch (\PDOException $e) {
            throw new CanNotExecuteQuery($e->getMessage());
        }

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public function readAll($query, array $placeholders = [])
    {
        $statement = $this->pdo->prepare($query);

        try {
            $statement->execute($placeholders);
        } catch (\PDOException $e) {
            throw new CanNotExecuteQuery($e->getMessage());
        }

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}
