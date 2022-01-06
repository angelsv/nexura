<?php

declare(strict_types=1);
namespace App\Models;

use App\Models\AreaDao;
use App\Models\EmployeeRoleDao;

class EmployeeDao extends GetConnection
{
    private $table = 'empleado';

    /**
     * Obtener todos los registros
     */
    function getAll() : array
    {
        $query = "SELECT * FROM `{$this->table}`";
        $result = $this->connection->query($query);
        $all = $result->fetchAll();
        foreach ($all as $key => $item) {
            $all[$key] = $this->addParamsToEmployee($item);
        }
        // echo '<pre>';
        // print_r($all);
        // exit;
        return $all;
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
        $employee = $stmt->fetch();
        if( $employee!==false ){
            $employee = $this->addParamsToEmployee($employee);
        }
        // echo '<pre>';
        // print_r($employee);
        // exit;
        return $employee;
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
     * TODO: imeplementar campo `active` para evitar eliminar el registro por completo de la DB
     */
    function delete(int $employeeId) : bool
    {
        $query = "DELETE FROM `{$this->table}` WHERE `id` = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $employeeId);
        $res = $stmt->execute();

        // Si la foreign key está en cascada, NO se necesitaría eliminar los registros asociados
        // Eliminación manual:
        if($res !== false){
            $rolesDao = new EmployeeRoleDao;
            $rolesDao->deleteByEmployeeId((int)$employeeId);
        }

        return $res !== false;
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

    /**
     * Incluir roles del empleado
     */
    function addRolesToEmployee(array $employee) : array
    {
        if(!empty($employee['id'])){
            $rolesDao = new EmployeeRoleDao;
            $employee['roles'] = $rolesDao->getAllByEmployeeId((int)$employee['id']);
        }
        return $employee;
    }
    
    /**
     * Incluir área del empleado
     */
    function addAreaToEmployee(array $employee) : array
    {
        $areaDao = new AreaDao;
        $employee['area'] = $areaDao->getById((int)$employee['area_id']);
        return $employee;
    }

    /**
     * Añadir los parámetros adicionales como arreglos para facilitar la lectura
     */
    function addParamsToEmployee(array $employee) : array
    {
        $employee = $this->addRolesToEmployee($employee);
        $employee = $this->addAreaToEmployee($employee);
        return $employee;
    }
}