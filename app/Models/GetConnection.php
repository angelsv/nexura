<?php

declare(strict_types=1);
namespace App\Models;

class GetConnection
{
    protected $connection;

    function __construct()
    {
        $this->connection = Connection::getInstance()->getConnection();
    }
}
