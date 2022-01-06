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

    /**
     * Cargar un área por su id
     */
    function getById(int $id)
    {
        $query = "SELECT * FROM `{$this->table}` WHERE `id` = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch();
    }

}