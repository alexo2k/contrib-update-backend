<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TokenProvider;
use Illuminate\Support\Facades\DB;

class DockBoxController extends Controller
{
    public function tramitesEmpleado(Request $request) {
        
        $auxIdDocBox = $request->input('idDocBox', null);
        $auxEmp = $request->input('employee', null);
        $aux = json_decode($auxEmp);
        $token = $request->header('tokenapp');
        $validUser = TokenProvider::validateToken($auxEmp, $token);

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
