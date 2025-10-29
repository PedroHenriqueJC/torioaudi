<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EquipamentoSala extends Pivot
{
    protected $table = 'equipamento_sala';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'sala_cod_sala',
        'equipamento_cod_equipamento',
        'quantidade_equipamento',
    ];
}
