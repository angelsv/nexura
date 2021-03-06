<?php

declare(strict_types=1);
namespace App\Controllers;
session_start();

use App\Models\AreaDao;
use App\Models\RoleDao;
use App\Models\Employee;
use App\Models\EmployeeDao;
use App\Models\EmployeeRoleDao;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class EmployeeController extends Controller
{

    private $areas;
    private $roles;
    private $employeeDao;
    private $validator;

    function __construct(){
        parent::__construct();
        $areaModel = new AreaDao;
        $roleModel = new RoleDao;
        $this->validator = new EmailValidator();
        $this->employeeDao = new EmployeeDao;
        $this->areas = $areaModel->getAll();
        $this->roles = $roleModel->getAll();
    }

    /**
     * Cargar el formulario de creación/edición
     */
    function showForm(){
        $_SESSION['csrf_token'] = bin2hex(random_bytes(35));
        $this->template->twig->display('form.html', [
            'areas' => $this->areas, 
            'roles' => $this->roles, 
            'action' => '/employee', 
            'csrf_token' => $_SESSION['csrf_token']
        ]);
    }

    /**
     * Cargar la lista de empleados
     */
    function getAll($data = []){
        $employees = $this->employeeDao->getAll();
        $data['employees'] = $employees;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(35));
        $data['csrf_token'] = $_SESSION['csrf_token'];
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
        $_SESSION['csrf_token'] = bin2hex(random_bytes(35));
        $this->template->twig->display('form.html', [
            'employee' => $employee,
            'areas' => $this->areas,
            'roles' => $this->roles,
            'action' => "/employee/{$id}",
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }

    /**
     * Eliminar un registro
     */
    function delete(){
        $id = filter_var(trim($_POST['id']), FILTER_SANITIZE_NUMBER_INT);
        $token = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_STRING);
        $is_not_valid = $token !== $_SESSION['csrf_token'];

        if( $is_not_valid ){
            $response = [
                'response' => false,
                'msg' => 'Hay un error de seguridad, por favor recargue la página',
            ];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response);
            return null;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(35));

        $res = $this->employeeDao->delete((int)$id);
        $response = [
            'response' => $res,
            'msg' => $res ? 'Registro eliminado correctamente!' : 'Ocurrió un error eliminado el registro',
            'csrf_token' => $_SESSION['csrf_token'],
        ];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        return null;
    }

    /**
     * Proceso para guardar el registro
     */
    function save(){

        $name = filter_var(trim($_POST['employee']['nombre']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['employee']['email']), FILTER_SANITIZE_EMAIL);
        $gender = isset($_POST['employee']['sexo']) ? filter_var(trim($_POST['employee']['sexo']), FILTER_SANITIZE_STRING) : null;
        $area = isset($_POST['employee']['area_id']) ? filter_var(trim($_POST['employee']['area_id']), FILTER_SANITIZE_NUMBER_INT) : null;
        $description = filter_var(trim($_POST['employee']['descripcion']), FILTER_SANITIZE_STRING);
        $roles = $_POST['employee']['roles'] ?? null;
        
        $newsletter = isset($_POST['employee']['boletin']) ? $_POST['employee']['boletin'] : '0';
        $newsletter = filter_var(trim($newsletter), FILTER_SANITIZE_NUMBER_INT);

        $token = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_STRING);

        // Validación de campos
        $errors = [];
        if( $token !== $_SESSION['csrf_token'] ){
            $errors[] = 'Hay un error de seguridad, por favor recargue la página';
        }
        if( empty($name) ){
            $errors[] = 'El campo `nombre` es obligatorio';
        }
        if( empty($email) ){
            $errors[] = 'El campo `email` es obligatorio';
        }
        if( !empty($email) && !$this->validator->isValid($email, new RFCValidation()) ){
            $errors[] = 'El campo `email` debe tener un formato correcto';
        }
        if( empty($gender) || ($gender!=='M' && $gender!=='F') ){
            $errors[] = 'El campo `sexo` es obligatorio';
        }
        if( empty($area) || !ctype_digit("{$area}") ){
            $errors[] = 'El campo `área` es obligatorio';
        }
        if( empty($description) ){
            $errors[] = 'El campo `descripción` es obligatorio';
        }
        if( empty($roles) ){
            $errors[] = 'El campo `roles` es obligatorio (debe tener al menos una opción seleccionada)';
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(35));

        if( count($errors) > 0 ){
            $response = [
                'response' => false,
                'msg' => 'Se encontraron errores en el formulario, por favor valide e intente nuevamente:',
                'errors' => $errors,
            ];
            $this->template->twig->display('form.html', [
                'employee' => $_POST['employee'], 
                'response' => $response, 
                'areas' => $this->areas, 
                'roles' => $this->roles, 
                'action' => '/employee', 
                'csrf_token' => $_SESSION['csrf_token'],
            ]);
            return null;
        }

        // Procesar solicitud sin errores
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
            $employeeRoleDao = new EmployeeRoleDao;
            $employee['roles'] = $employeeRoleDao->insertRolesByEmployeeId((int)$employee['id'], $roles);
        }

        if( $exists_user ){
            $this->template->twig->display('form.html', [
                'employee' => $employee,
                'response' => $response,
                'areas' => $this->areas,
                'roles' => $this->roles,
                'action' => "/employee/{$id}",
                'csrf_token' => $_SESSION['csrf_token'],
            ]);
        }else{
            $this->getAll(['response' => $response]);
        }

    }

}