<?php

namespace App\Database;

abstract class Database
{
    private string $host;
    private string $username;
    private string $password;

    public function __construct(string $host, string $username, string $password) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    abstract protected function connect();
}