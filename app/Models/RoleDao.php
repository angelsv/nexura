<?php

declare(strict_types=1);
namespace App\Models;

class RoleDao extends GetConnection
{
    private $table = 'roles';

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