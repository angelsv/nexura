<?php

declare(strict_types=1);
namespace App\Models;

class AreaDao
{
    private $connection;
    private $table = 'areas';

    function __construct()
    {
        $this->connection = Connection::getInstance()->getConnection();
    }

    /**
     * Obtener todos los registros
     */
    function getAll() : array
    {
        $query = "SELECT * FROM `{$this->table}`";
        $result = $this->connection->query($query);
        return $result->fetchAll();
    }

}