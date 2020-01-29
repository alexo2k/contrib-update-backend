<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debit extends Model
{
    // public $Id_Empleado;
    // public $AdeudoPrestamo;
    // public $FechaTerminoAdeudo;
    public $table = 'adeudo';
    protected $connection = 'contribDB';

}
