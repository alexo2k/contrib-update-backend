<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TokenProvider;
use Illuminate\Support\Facades\DB;
use App\Employee;

class DockBoxController extends Controller
{
    public function tramitesEmpleado(Request $request) {
        
        $auxIdDocBox = $request->input('idDocBox', null);
        $auxEmp = $request->input('employee', null);
        
        $employeeParams = json_decode($auxEmp);
        $employeeModel = new Employee();
        $employeeModel->Id_Empleado = $employeeParams->Id_Empleado;
        $employeeModel->ApPaterno = $employeeParams->ApPaterno;
        $employeeModel->ApMaterno = $employeeParams->ApMaterno;
        $employeeModel->Nombre = $employeeParams->Nombre;
        $employeeModel->RFC = $employeeParams->RFC;

        $token = $request->header('tokenapp', null);

        $validUser = TokenProvider::validateToken($employeeModel, $token);

        if($validUser) {
            $auxTramites = DB::connection('docBoxDB')->select('SELECT info.asunto, info.fecha_recepcion, secre.secretaria, info.estatus from informacion info join secretarias secre on info.id_secretaria = secre.Id_Secretaria where info.id_trabajador = 200');

            return response()->json(array(
                'tramites' => $auxTramites,
                'status' => 'success',
            ), 200); 
        } else {
            return response()->json(array(
                'message' => 'las credenciales no son válidas',
                'status' => 'error'
            ), 400);
        }

        return $auxTramites;
    }

    public function tramitesSecretaria(Request $request) {
        
        $auxIdDocBox = $request->input('idDocBox', null);
        $auxEstado = $request->input('edoDocBox');
        $auxEmp = $request->input('employee', null);
        $fechaInicial = $request->input('initialDate');
        $fechaFinal = $request->input('finalDate');

        if($auxEstado == 0) {
            return response()->json(array(
                'message' => 'El usuario no tiene asignada una secretaría',
                'status' => 'error'
            ), 400);
        } else {
            $employeeParams = json_decode($auxEmp);
            $employeeModel = new Employee();
            $employeeModel->Id_Empleado = $employeeParams->Id_Empleado;
            $employeeModel->ApPaterno = $employeeParams->ApPaterno;
            $employeeModel->ApMaterno = $employeeParams->ApMaterno;
            $employeeModel->Nombre = $employeeParams->Nombre;
            $employeeModel->RFC = $employeeParams->RFC; 
    
            $token = $request->header('tokenapp', null);
    
            $validUser = TokenProvider::validateToken($employeeModel, $token);
            
            if($validUser) {
            $auxSecretaria = DB::connection('docBoxDB')->select("select secre.Secretaria, info.Fecha_Recepcion, info.Control_Interno, info.Numero_Oficio, info.Oficio_Referencia, info.Medio_Entrega, info.Procedencia, info.NombreEscrito, info.Asunto, info.Control_Seguridad, info.Estatus, info.Resumen_Gral from informacion info JOIN secretarias secre on info.Id_Secretaria = secre.Id_Secretaria where info.Id_Estado = $auxEstado and info.Fecha_Recepcion > '$fechaInicial' and info.Fecha_Recepcion < '$fechaFinal'");
               return response()->json(array(
                    'tramites'=> $auxSecretaria,
                    'status' => 'success'
                    // ,'conteo' => count($auxSecretaria)
               ), 200);  
            } else {
                return response()->json([
                    'message' => 'El usuario no está autenticado',
                    'status' => 'error'
                ], 400);
            }
        }
        
    }

}
