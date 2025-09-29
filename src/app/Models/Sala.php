<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sala extends Model
{
    use HasFactory;

    protected $table = 'salas';
    protected $primaryKey = 'cod_sala';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nome_sala',
        'capacidade_sala',
        'descricao_sala',
        'localizacao_sala',
    ];

    // Uma sala pode ter vários eventos
    public function eventos()
    {
        return $this->hasMany(Evento::class, 'sala_cod_sala', 'cod_sala');
    }

    // Relação Many-to-Many com equipamentos
    public function equipamentos()
    {
        return $this->belongsToMany(Equipamento::class, 'equipamento_sala', 'sala_cod_sala', 'equipamento_cod_equipamento')
                    ->withPivot('quantidade_equipamento');
    }
}
