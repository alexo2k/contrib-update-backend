<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocBox extends Model
{
    protected $primaryKey = "id_trabajador";
    public $table = "empleados";
    protected $connection = "docBoxDB";
}
