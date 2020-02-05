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

    public function tramitesSecretaria($idEstado) {

    }
}
