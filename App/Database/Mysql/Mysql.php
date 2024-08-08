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

    protected function connect()
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

    public function select(array $params, string $table, string $where)
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
    
            if (isset($where)) {
                $query .= " WHERE :conditions";
                $stmt = $this->getConn()->prepare($query);
                $stmt->bindParam(":conditions", $where);
            } else {
                $stmt = $this->getConn()->prepare($query);
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

    public function insert(array $params, string $table)
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
        } catch (PDOException $e) {
            echo sprintf(
                "Code: %s \n Message: %s", 
                $e->getCode() ?: 400, 
                $e->getMessage()
            );
        }
    }

    public function update(array $params, string $table, string $where)
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
                "UPDATE %s SET (%s) WHERE :conditions", 
                $table,
                $valuesToUpdate
            );

            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindParam(":conditions", $where);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo sprintf(
                "Code: %s \n Message: %s", 
                $e->getCode() ?: 400, 
                $e->getMessage()
            );
        }
    }

    public function delete(string $table, string $where)
    {
        try {
            if (!isset($table) || $table == "") {
                throw new PDOException("Table name is required", 400);
            }

            if (!isset($where)) {
                throw new PDOException("Delete condition is required", 400);
            }
            
            $sql = sprintf(
                "DELETE FROM (%s) WHERE :conditions", 
                $table
            );

            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindParam(":conditions", $where);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo sprintf(
                "Code: %s \n Message: %s", 
                $e->getCode() ?: 400, 
                $e->getMessage()
            );
        }
    }
}