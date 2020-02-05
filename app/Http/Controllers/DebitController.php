<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Debit;
use TokenProvider;
use App\Employee;

class DebitController extends Controller
{
    public function recuperaAdeudo(Request $request)
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
            $adeudo = Debit::where('Id_empleado', $employeeParams->Id_Empleado)->first();

            if($adeudo) {
                return response()->json(array(
                    'adeudo' => $adeudo,
                    'status' => 'success',
                ), 200);   
            } else {
                return response()->json(array(
                    'message' => 'No se encontro el empleado',
                    'status' => 'error',
                ), 400);
            }
        } else {
            return response()->json(array(
                'message' => 'Las credenciales no son validas',
                'status' => 'error'
            ), 401);
        }
    }
}
