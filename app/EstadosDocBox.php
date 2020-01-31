<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadosDocBox extends Model
{
    protected $primaryKey = "Id_Estado";
    public $table = "estados";
    protected $connection = "docBoxDB";
}
