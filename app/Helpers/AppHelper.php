<?php

namespace App\Helpers;
use App\Employee;

class AppHelper 
{    
    public static function makeToken(Employee $pEmployee) {
        $auxToken = $pEmployee->Id_Empleado . substr($pEmployee->ApPaterno, 0, 4) . substr($pEmployee->Nombre, 0, 3) . substr($pEmployee->RFC, 2, 3) . date('Ymd');
        return $auxToken;
    }

    public static function saluda() {
        return "hola";
    }

    public static function validateToken(Employee $pEmployee, $token) {
        $generatedToken = AppHelper::makeToken($pEmployee);
        if($token == $generatedToken) {
            return true;
        } else {
            return false;
        }
    }
}
