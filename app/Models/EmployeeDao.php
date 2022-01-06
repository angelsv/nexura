<?php

declare(strict_types=1);
namespace App\Models;

class EmployeeDao
{
    private $connection;
    private $table = 'empleado';

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
     * Cargar un empleado por su id
     */
    function getById(int $id)
    {
        $query = "SELECT * FROM `{$this->table}` WHERE `id` = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Crear nuevos
     */
    function insert(Employee $employee) : array
    {
        $query = "INSERT INTO `{$this->table}` (`nombre`, `email`, `sexo`, `area_id`, `boletin`, `descripcion`) VALUES (?,?,?,?,?,?)";
        $stmt = $this->connection->prepare($query);

        $getName = $employee->getNombre();
        $getEmail = $employee->getEmail();
        $getGender = $employee->getSexo();
        $getAreaId = $employee->getAreaId();
        $getNewsletter = $employee->getBoletin();
        $getDescription = $employee->getDescripcion();

        $stmt->bindParam(1, $getName);
        $stmt->bindParam(2, $getEmail);
        $stmt->bindParam(3, $getGender);
        $stmt->bindParam(4, $getAreaId);
        $stmt->bindParam(5, $getNewsletter);
        $stmt->bindParam(6, $getDescription);
        $stmt->execute();

        $id = $this->connection->lastInsertId();
        return $this->getById((int)$id);
    }

    /**
     * Editar antiguos
     */
    function update(Employee $employee) : array
    {
        $query = "UPDATE `{$this->table}` SET `nombre` = ?, `email` = ?, `sexo` = ?, `area_id` = ?, `boletin` = ?, `descripcion` = ? WHERE `id` = ?";
        $stmt = $this->connection->prepare($query);

        $getName = $employee->getNombre();
        $getEmail = $employee->getEmail();
        $getGender = $employee->getSexo();
        $getAreaId = $employee->getAreaId();
        $getNewsletter = $employee->getBoletin();
        $getDescription = $employee->getDescripcion();
        $getId = $employee->getId();

        $stmt->bindParam(1, $getName);
        $stmt->bindParam(2, $getEmail);
        $stmt->bindParam(3, $getGender);
        $stmt->bindParam(4, $getAreaId);
        $stmt->bindParam(5, $getNewsletter);
        $stmt->bindParam(6, $getDescription);
        $stmt->bindParam(7, $getId);
        $stmt->execute();

        return $this->getById((int)$employee->getId());
    }
}