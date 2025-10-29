<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipamento extends Model
{
    use HasFactory;

    protected $table = 'equipamento';
    protected $primaryKey = 'cod_equipamento';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nome_equipamento',
        'descricao_equipamento',
        'quantidade_equipamento',
    ];

    // Relação Many-to-Many com salas
    public function salas()
    {
        return $this->belongsToMany(Sala::class, 'equipamento_sala', 'equipamento_cod_equipamento', 'sala_cod_sala')->using(EquipamentoSala::class)
                    ->withPivot('quantidade_equipamento');
    }

    // Relação Many-to-Many com eventos
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'equipamento_evento', 'equipamento_cod_equipamento', 'evento_cod_evento')
                    ->withPivot('quantidade_equipamento_emprestado');
    }
}
