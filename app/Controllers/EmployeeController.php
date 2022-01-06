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
    private $employeeDao;

    function __construct(){
        parent::__construct();
        $areaModel = new AreaDao;
        $roleModel = new RoleDao;
        $this->employeeDao = new EmployeeDao;
        $this->areas = $areaModel->getAll();
        $this->roles = $roleModel->getAll();
    }

    /**
     * Cargar el formulario de creación/edición
     */
    function showForm(){
        $this->template->twig->display('form.html', ['areas' => $this->areas, 'roles' => $this->roles, 'action' => '/employee']);
    }

    /**
     * Cargar la lista de empleados
     */
    function getAll($data = []){
        $employees = $this->employeeDao->getAll();
        $data['employees'] = $employees;
        $this->template->twig->display('list.html', $data);
    }
    
    /**
     * Cargar un empleado por su id
     */
    function getById($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $employee = $this->employeeDao->getById((int)$id);
        if( empty($employee) ){
            // TODO: implementar función error404
            echo 'error404';
            // exit;
        }
        $this->template->twig->display('form.html', ['employee' => $employee, 'areas' => $this->areas, 'roles' => $this->roles, 'action' => "/employee/{$id}"]);
    }

    /**
     * Eliminar un registro
     */
    function delete(){
        $id = filter_var(trim($_POST['id']), FILTER_SANITIZE_NUMBER_INT);
        $res = $this->employeeDao->delete((int)$id);
        $response = [
            'response' => $res,
            'msg' => $res ? 'Registro eliminado correctamente!' : 'Ocurrió un error eliminado el registro',
        ];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    /**
     * Proceso para guardar el registro
     */
    function save(){

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

        // Procesar creación/actualización
        $exists_user = isset($_POST['employee']['id']) && !empty($this->employeeDao->getById((int)$_POST['employee']['id']));
        if( $exists_user ){
            $id = filter_var(trim($_POST['employee']['id']), FILTER_SANITIZE_NUMBER_INT);
            $employeeDto->setId((int)$id);
            $employee = $this->employeeDao->update($employeeDto);
            $response = [
                'response' => !empty($employee),
                'msg' => !empty($employee) ? 'Registro actualizado satisfactoriamente!' : 'Error actualizando el registro',
            ];
        }else{
            $employee = $this->employeeDao->insert($employeeDto);
            $response = [
                'response' => !empty($employee),
                'msg' => !empty($employee) ? 'Registro creado satisfactoriamente!' : 'Error creando el registro',
            ];
        }

        // Los roles sólo se gestionan cuando la creación/actualización fue exitosa
        if( $response['response'] ){
            $roles = $_POST['employee']['roles'];
            $employeeRoleDao = new EmployeeRoleDao;
            $employee['roles'] = $employeeRoleDao->insertRolesByEmployeeId((int)$employee['id'], $roles);
        }

        if( $exists_user ){
            $this->template->twig->display('form.html', ['employee' => $employee , 'response' => $response, 'areas' => $this->areas, 'roles' => $this->roles, 'action' => "/employee/{$id}"]);
        }else{
            $this->getAll(['response' => $response]);
        }

    }

}