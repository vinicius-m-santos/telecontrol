<?php

namespace App\Models;

use App\Database\Connection;
use App\Database\Database;

abstract class Model
{
    private Database $connection;

    public function __construct()
    {
        $this->connection = Connection::getConnection();
    }
    /**
     * Loads an entity from database
     * 
     * @param string $id
     * @return $this
     */
    abstract function load(string $id): self;

    /**
     * Saves model data to database
     *
     * @return void
     */
    abstract function save();

    /**
     * Get current database connection
     * 
     * @return Database
     */
    protected function getConnection(): Database
    {
        return $this->connection;
    }
}