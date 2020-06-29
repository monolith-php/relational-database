<?php namespace Monolith\RelationalDatabase;

use PDO;

class Db
{
    /** @var PDO */
    private $pdo;

    public function __construct($dsn, $username, $password)
    {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            $message = $e->getMessage() == "The provided DSN configuration '{$dsn}' is invalid. This is typically defined in your .env file."
                ? "Could not parse DSN '{$dsn}'. This is typically defined in your .env file."
                : $e->getMessage();

            throw new CouldNotConnectWithPdo($message);
        }
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->pdo->commit();
    }

    public function rollbackTransaction(): void
    {
        $this->pdo->rollBack();
    }

    public function write($query, array $placeholders = []): void
    {
        $statement = $this->pdo->prepare($query);

        try {
            $statement->execute($placeholders);
        } catch (\PDOException $e) {
            throw new CanNotExecuteQuery("Can not execute database query: $e->getMessage()");
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
