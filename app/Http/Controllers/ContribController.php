<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Contribution;
use App\Employee;
use TokenProvider;

class ContribController extends Controller
{
    public function recuperaAportacion(Request $request)
    {
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
            $aportacion = Contribution::where('Id_empleado', $employeeParams->Id_Empleado)->first();

            if($aportacion) {
                return response()->json(array(
                    'aportacion' => $aportacion,
                    'status' => 'success',
                ), 200);   
            } else {
                return response()->json(array(
                    'message' => 'No se encontro la aportacion',
                    'status' => 'error',
                ), 400);
            }
        } else {
            return response()->json(array(
                'message' => 'La credencial no es valida',
                'status' => 'error'
            ), 401);
        }

        
    }

}
