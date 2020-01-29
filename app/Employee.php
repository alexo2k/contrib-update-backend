<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = 'Id_Empleado'; 
    public $table = 'datospersonales';
    protected $connection = 'contribDB';
}
