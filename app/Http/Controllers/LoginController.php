<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Employee;
use TokenProvider;
use App\EmployeeDocBox;
use App\EstadosDocBox;
use GuzzleHttp\Client;

class LoginController extends Controller
{
    public function recuperaIdDocBox(Request $request) {

        $json = $request->input('rfcEmpleado', null);
        $empleadoParam = json_decode($json);

        $empleadoDocBox = EmployeeDocBox::where('Filiacion', $empleadoParam->rfcEmpleado)->first();

        if($empleadoDocBox) {
            return response()->json(array(
                'empleadoDocBox' => $empleadoDocBox,
                'status' => 'success'
            ), 200);
        } else {
            return response()->json(array(
                'message' => 'No se encontró el empleado',
                'status' => 'error'
            ), 400);
        }
    }

    public function validaAcceso(Request $request) 
    {
        // $aux = $this->makeToken($newEmp);
        // $aux = Helpers::instance()->makeToken($newEmp);
        // $aux = \App\Helpers\AppHelper::instance()->saluda();
        // $aux = TokenProvider::makeToken($newEmp);
        // $result = TokenProvider::validateToken($newEmp, $aux);

        $empleadoDocBoxId = null;
        $estadoDocBox = null;

        $json = $request->input('userpass', null);
        $passwordParam = json_decode($json);
        
        $empleadosResult = DB::select('SELECT ValidaCredencial(\'' . $passwordParam->passuser . '\') AS Id_Empleado');

        if($empleadosResult) {

            foreach($empleadosResult as $result) {
                $auxIdEmpleado = $result -> Id_Empleado;
            }

            $empleado = Employee::find($auxIdEmpleado);

            if($empleado) { 

                try {

                    DB::select('UPDATE StatusEmpleado SET UltimaVisita = now() WHERE Id_Empleado =' . $auxIdEmpleado);
                    $auxEmpleadoDocBox = EmployeeDocBox::where('Filiacion', $empleado->RFC)->first();

                    if($auxEmpleadoDocBox) {
                        $empleadoDocBoxId = $auxEmpleadoDocBox->id_trabajador;
                        $auxEstadosDocBox = DB::connection('docBoxDB')->select('SELECT * FROM estados where id_trabajador = ' . $empleadoDocBoxId);

                        if($auxEstadosDocBox) {
                            
                            foreach($auxEstadosDocBox as $result) {
                                $estadoDocBox = array(
                                    'idEstadoDocBox' => $result->Id_Estado,
                                    'EstadoDocBox' => $result->Estados
                                );
                            }
                        } else {
                            $estadoDocBox = array(
                                'idEstadoDocBox' => '0',
                                'EstadoDocBox' => 'none'
                            );
                        }
                    } else {
                        $empleadoDocBoxId = 0;
                    }

                } catch(Exception $ex) {
                    // Insertar nuevo empleado
                }


                $accessToken = TokenProvider::makeToken($empleado);

                return response()->json(array(
                    'empleado' => $empleado,
                    'empleadoDocBoxId' => $empleadoDocBoxId,
                    'estadoDocBox' => $estadoDocBox,
                    'token' => $accessToken,
                    'status' => 'success'
                ), 200);
            } else {
                return response()->json(array(
                    'message' => 'No se encontró información del empleado',
                    'status' => 'error',
                ), 400);
            }

        } else {
            return response()->json(array(
                'message' => 'No existe el empleado',
                'status' => 'error'
            ), 400);
        }
    }

    public function validaCaptcha(Request $request) {
        
        $recaptchaKey = "6LclvqgZAAAAAPTcy0-98_sHB1b2f65fgFf-moyc";
        $url = "https://www.google.com/recaptcha/api/siteverify";
        
        $json = $request->input('tokenCaptcha', null);
        $tokenCaptcha = json_decode($json);

        $client = new Client();

        $response = $client->request('POST', $url, [
            'query' => [
                'secret' => $recaptchaKey,
                'response' => $tokenCaptcha->token
            ]
        ]);

        if($response) {
            $responsed = $response->getBody()->getContents();
            $jsonedResponse = json_decode($responsed);

            if($jsonedResponse->success) {
                return response()->json(array(
                    'message' => 'Se ha validado',
                    'status' => 'success'
                ), 200);
            } else {
                return response()->json(array(
                    'message' => $jsonedResponse->{'error-codes'},
                    'status' => 'error'
                ), 400);
            }
        } else {
            return response()->json(array(
                'message' => $respone,
                'status' => 'error'
            ), 400);
        }
    }
}
