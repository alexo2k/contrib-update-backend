<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Contribution;
use App\Credential;

class ContribController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $prueba = DB::select('select * from adeudo where Id_Empleado = 2');

        // $contrib = Contribution::where('Id_Empleado', 2)->first();

        // return $contrib;

        // return Credential::where('Id_Empleado', 2)->first();
        // $credencial = Credential::Where('Id_Empleado', 2)->first();
        $idEmpleado = DB::select('SELECT ValidaCredencial(\'CGMQBZZioiamFAS\') AS Id_Empleado');

        return $idEmpleado;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $auxEmp = $request->input('employee', null);
        $employeeParams = json_decode($auxEmp);
        $token = $request->header('tokenapp', null);
        $validUser = TokenProvider::validateToken($auxEmp, $token);

        if($validUser) {
            $empleado = Contribution::where('Id_empleado', $employeeParams->id_empleado)->first();

            if($empleado) {
                return response()->json(array(
                    'empleado' => $empleado,
                    'status' => 'success',
                ), 200);   
            } else {
                return response()->json(array(
                    'message' => 'No se encontró el empleado',
                    'status' => 'error',
                ), 400);
            }
        } else {
            return response()->json(array(
                'message' => 'Las credenciales no son válidas',
                'status' => 'error'
            ), 401);
        }

        
    }

}
