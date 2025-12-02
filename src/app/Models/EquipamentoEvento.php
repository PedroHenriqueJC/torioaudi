<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EquipamentoEvento extends Pivot
{
    protected $table = 'equipamento_evento';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'evento_cod_evento',
        'equipamento_cod_equipamento',
        'quantidade_equipamento_emprestado',
    ];
}
