<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'evento';
    protected $primaryKey = 'cod_evento';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'evento_inicio',
        'evento_fim',
        'nome_evento',
        'descricao_evento',
        'cancelado_evento',
        'pre_agenda_evento',
        'usuario_cod_usuario',
        'sala_cod_sala',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_cod_usuario', 'cod_usuario');
    }

    // Um evento acontece em uma sala
    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_cod_sala', 'cod_sala');
    }

    // Um evento pode ter vários equipamentos (com quantidade emprestada)
    public function equipamentos()
    {
        return $this->belongsToMany(Equipamento::class, 'equipamento_evento', 'evento_cod_evento', 'equipamento_cod_equipamento')
                    ->withPivot('quantidade_equipamento_emprestado');
    }
}
