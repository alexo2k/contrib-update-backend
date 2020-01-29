<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    public $Id_Empleado;
    public $AportacionSindical;
    public $timestamps = false;

    protected $connection = 'contribDB';
    protected $table = 'acumuladoaportaciones';
}
