<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Debit;
use TokenProvider;

class DebitController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $auxEmp = $request->input('employee', null);
        $aux = json_decode($auxEmp);
        $token = $request->header('tokenapp');
        $validUser = TokenProvider::validateToken($auxEmp, $token);
        
        if($validUser) {
            $adeudo = Debit::where('Id_empleado', $idEmpleado)->first();

            if($adeudo) {
                return response()->json(array(
                    'adeudo' => $adeudo,
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
