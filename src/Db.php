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
            $message = $e->getMessage() == "invalid data source name"
                ? "Could not parse data source name '{$dsn}'."
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
