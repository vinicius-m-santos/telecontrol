<?php

namespace App\Database\Mysql;

use App\Database\Database;
use PDO;
use PDOException;

class Mysql extends Database
{
    private string $dbname;
    private string $host;
    private string $username;
    private string $password;
    private PDO $mysqlConn;

    public function __construct(
        string $dbname,
        string $host,
        string $username,
        string $password
    )
    {
        $this->setDbname($dbname);
        $this->setHost($host);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->connect();
    }

    /**
     * Connects to database
     *
     * @return void
     */
    protected function connect(): void
    {
        try {
            $mysqlConn = new PDO(sprintf(
                    "mysql:host=%s;dbname=%s", 
                    $this->getHost(),
                    $this->getDbname()
                ), 
                $this->getUsername(), 
                $this->getPassword()
            );
            $mysqlConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->mysqlConn = $mysqlConn;
          } catch(\PDOException $e) {
            printf(sprintf("Connection failed: %s", $e->getMessage()));
          }
    }

    /**
     * Get Database name
     *
     * @return string
     */
    private function getDbname(): string
    {
        return $this->dbname;
    }

    /**
     * Set Database name
     *
     * @return void
     */
    private function setDbname($dbname)
    {
        $this->dbname = $dbname;
        return $this;
    }

    /**
     * Get Database host
     *
     * @return string
     */
    private function getHost(): string
    {
        return $this->host;
    }

    /**
     * Set Database host
     *
     * @return void
     */
    private function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get Database password
     *
     * @return string
     */
    private function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set Database password
     *
     * @return void
     */
    private function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get Database username
     *
     * @return string
     */
    private function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set Database username
     *
     * @return void
     */
    private function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get current connection
     *
     * @return PDO
     */
    private function getConn(): PDO
    {
        return $this->mysqlConn;
    }

    /**
     * Select data from database
     *
     * @param array $params
     * @param string $table
     * @param string $where
     * @param array $whereParams
     * @return array
     */
    public function select(array $params, string $table, string $where = "", array $whereParams = []): array
    {
        try {
            if (!isset($table) || $table == "") {
                throw new PDOException("Table name is required", 400);
            }

            if (count($params) == 0) {
                $params[] = "*";
            }

            $query = sprintf(
                "SELECT %s FROM %s", 
                implode(", ", $params), 
                $table
            );

            if (!empty($where)) {
                $query .= " WHERE $where";
            }
    
            $stmt = $this->getConn()->prepare($query);
            foreach ($whereParams as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
    
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo sprintf(
                "Code: %s \n Message: %s", 
                $e->getCode() ?: 400, 
                $e->getMessage()
            );
        }
    }

    /**
     * Inserts data into database
     *
     * @param array $params
     * @param string $table
     * @return string|false
     */
    public function insert(array $params, string $table): string|false
    {
        try {
            if (!isset($table) || $table == "") {
                throw new PDOException("Table name is required", 400);
            }

            if (count($params) == 0) {
                throw new PDOException("Missing params to insert", 400);
            }

            $keys = array_keys($params);
            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (:%s);", 
                $table,
                implode(", ", $keys),
                implode(", :", $keys)
            );

            $stmt = $this->getConn()->prepare($sql);
            foreach ($params as $index => $value) {
                $stmt->bindValue(sprintf(":%s", $index), $value);
            }
    
             $stmt->execute();
             return $this->getConn()->lastInsertId();
        } catch (PDOException $e) {
            echo sprintf(
                "Code: %s \n Message: %s", 
                $e->getCode() ?: 400, 
                $e->getMessage()
            );
        }
    }

    /**
     * Updates data in database
     * 
     * @param array $params
     * @param string $table
     * @param string $where
     * @param array $whereParams
     * @return bool
     * @throws PDOException
     */
    public function update(array $params, string $table, string $where, array $whereParams = []): bool
    {
        try {
            if (!isset($table) || $table == "") {
                throw new PDOException("Table name is required", 400);
            }

            if (count($params) == 0) {
                throw new PDOException("Missing params to update", 400);
            }

            if (!isset($where)) {
                throw new PDOException("Update condition is required", 400);
            }

            $keys = array_keys($params);
            $valuesToUpdate = "";
            foreach ($keys as $key) {
                $valuesToUpdate .= sprintf("%s = :%s,", $key, $key);
            }
            $valuesToUpdate = trim($valuesToUpdate, ",");

            $sql = sprintf(
                "UPDATE %s SET %s", 
                $table,
                $valuesToUpdate
            );

            if (!empty($where)) {
                $sql .= " WHERE $where";
            }
    
            $stmt = $this->getConn()->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            foreach ($whereParams as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
    
            return $stmt->execute();
        } catch (PDOException $e) {
            echo sprintf(
                "Code: %s \n Message: %s", 
                $e->getCode() ?: 400, 
                $e->getMessage()
            );
        }
    }

    /**
     * Deletes data from database
     *
     * @param string $table
     * @param string $where
     * @param array $whereParams
     * @return bool
     */
    public function delete(string $table, string $where, array $whereParams = []): bool
    {
        try {
            if (!isset($table) || $table == "") {
                throw new PDOException("Table name is required", 400);
            }

            if (!isset($where)) {
                throw new PDOException("Delete condition is required", 400);
            }
            
            $sql = sprintf(
                "DELETE FROM %s WHERE %s", 
                $table,
                $where
            );
    
            $stmt = $this->getConn()->prepare($sql);

            foreach ($whereParams as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Implement logs in future release
            echo sprintf(
                "Code: %s \n Message: %s", 
                $e->getCode() ?: 400, 
                $e->getMessage()
            );
            return false;
        }
    }

    /**
     * Begin database transaction
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->getConn()->beginTransaction();
    }

    /**
     * Commit database transaction
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getConn()->commit();
    }

    /**
     * Rollback database transaction
     *
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->getConn()->rollBack();
    }
}