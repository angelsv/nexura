<?php

declare(strict_types=1);
namespace App\Models;

class EmployeeRoleDao
{
    private $connection;
    private $table = 'empleado_rol';

    function __construct()
    {
        $this->connection = Connection::getInstance()->getConnection();
    }

    /**
     * Obtener todos los registros de un empleado
     */
    function getAllByEmployeeId(int $employeeId) : array
    {
        $query = "SELECT `rol_id` FROM `{$this->table}` WHERE `empleado_id` = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $employeeId);
        $stmt->execute();
        $all = $stmt->fetchAll();
        $fixed = [];
        foreach ($all as $key => $item) {
            $fixed[] = $item['rol_id'];
        }
        return $fixed;
    }

    /**
     * Crear múltiples registros por empleado
     */
    function insertRolesByEmployeeId(int $employeeId, array $roles) : array
    {
        $this->deleteByEmployeeId($employeeId);
        foreach ($roles as $key => $rol) {
            $this->insert($employeeId, (int)$rol);
        }
        return $this->getAllByEmployeeId($employeeId);
    }

    /**
     * Crear nuevos
     */
    function insert(int $employee_id, int $rol_id) : bool
    {
        $query = "INSERT INTO `{$this->table}` (`empleado_id`, `rol_id`) VALUES (?,?)";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(1, $employee_id);
        $stmt->bindParam(2, $rol_id);
        $res = $stmt->execute();

        return $res !== false;
    }
    
    /**
     * Eliminar los registros de un empleado
     */
    function deleteByEmployeeId(int $employee_id) : bool
    {
        $query = "DELETE FROM `{$this->table}` WHERE `empleado_id` = ?";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(1, $employee_id);
        $res = $stmt->execute();

        return $res !== false;
    }

}