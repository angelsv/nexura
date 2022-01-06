<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Models\AreaDao;
use App\Models\RoleDao;
use App\Models\Employee;
use App\Models\EmployeeDao;
use App\Models\EmployeeRoleDao;

class EmployeeController extends Controller
{

    private $areas;
    private $roles;

    function __construct(){
        parent::__construct();
        $areaModel = new AreaDao;
        $roleModel = new RoleDao;
        $this->areas = $areaModel->getAll();
        $this->roles = $roleModel->getAll();
    }

    /**
     * Cargar el formulario de creación/edición
     */
    function showForm(){
        $this->template->twig->display('form.html', ['areas' => $this->areas, 'roles' => $this->roles]);
    }

    /**
     * Cargar la lista de empleados
     */
    function getAll(){
        $employeesModel = new EmployeeDao;
        $employees = $employeesModel->getAll();
        $this->template->twig->display('list.html', ['employees' => $employees]);
    }
    
    /**
     * Cargar un empleado por su id
     */
    function getById($id){
        $employeesModel = new EmployeeDao;
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $employee = $employeesModel->getById((int)$id);
        if( empty($employee) ){
            // TODO: implementar función error404
            echo 'error404';
            // exit;
        }
        $this->template->twig->display('form.html', ['employee' => $employee, 'areas' => $this->areas, 'roles' => $this->roles]);
    }

    function save(){
        $employeeDao = new EmployeeDao;

        $name = filter_var(trim($_POST['employee']['nombre']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['employee']['email']), FILTER_SANITIZE_EMAIL);
        $gender = filter_var(trim($_POST['employee']['sexo']), FILTER_SANITIZE_STRING);
        $area = filter_var(trim($_POST['employee']['area_id']), FILTER_SANITIZE_NUMBER_INT);
        $description = filter_var(trim($_POST['employee']['descripcion']), FILTER_SANITIZE_STRING);

        $newsletter = isset($_POST['employee']['boletin']) ? $_POST['employee']['boletin'] : '0';
        $newsletter = filter_var(trim($newsletter), FILTER_SANITIZE_NUMBER_INT);

        $employeeDto = new Employee;
        $employeeDto->setNombre($name);
        $employeeDto->setEmail($email);
        $employeeDto->setSexo($gender);
        $employeeDto->setAreaId((int)$area);
        $employeeDto->setBoletin((int)$newsletter);
        $employeeDto->setDescripcion($description);
        // $employeeDto->setRole($role);

        // Procesar creación/actualización
        if( isset($_POST['employee']['id']) && !empty($employeeDao->getById((int)$_POST['employee']['id'])) ){
            $employeeDto->setId((int)$_POST['employee']['id']);
            $employee = $employeeDao->update($employeeDto);
            $response = [
                'response' => !empty($employee),
                'msg' => !empty($employee) ? 'Registro actualizado satisfactoriamente!' : 'Error actualizando el registro',
            ];
        }else{
            $employee = $employeeDao->insert($employeeDto);
            $response = [
                'response' => !empty($employee),
                'msg' => !empty($employee) ? 'Registro creado satisfactoriamente!' : 'Error creando el registro',
            ];
        }

        // Los roles sólo se gestionan cuando la creación/actualización fue exitosa
        if( $response['response'] ){
            $roles = $_POST['employee']['roles'];
            $employeeRoleDao = new EmployeeRoleDao;
            $employee['roles'] = $employeeRoleDao->insertRolesByEmployeeId((int)$_POST['employee']['id'], $roles);
        }

        $this->template->twig->display('form.html', ['employee' => $employee , 'response' => $response, 'areas' => $this->areas, 'roles' => $this->roles]);

    }

}