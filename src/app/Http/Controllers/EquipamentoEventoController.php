<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Equipamento;
use Illuminate\Http\Request;

class EquipamentoEventoController extends Controller{
    public function showByEvento($eventoId){
        $evento = Evento::with('equipamentos')->findOrFail($eventoId);
        return response()->json($evento->equipamentos, 200);
    }

    public function showByEquipamento($equipamentoId){
        $equipamento = Equipamento::with('eventos')->findOrFail($equipamentoId);
        return response()->json($equipamento->eventos, 200);
    }

    public function store(Request $request){
        $request->validate([
            "evento_cod_evento" => 'required|exists:evento,cod_evento',
            "equipamento_cod_equipamento" => 'required|exists:equipamento,cod_equipamento',
            "quantidade_equipamento_emprestado" => 'required|integer|min:0',
        ]);

        $evento = Evento::findOrFail($request->evento_cod_evento);

        $usuario = auth()->user();

        if (!$usuario->is_admin && $evento->usuario_cod_usuario !== $usuario->cod_usuario) {
            abort(403, 'Você não tem permissão para modificar os equipamentos deste evento');
        }

        $evento->equipamentos()->syncWithoutDetaching([
            $request->equipamento_cod_equipamento => [
                'quantidade_equipamento_emprestado' => $request->quantidade_equipamento_emprestado
            ]
        ]);

        return response()->json(['message' => 'Equipamento adicionado ou atualizado com sucesso'], 200);
    }

    public function update(Request $request){
        $request->validate([
            'quantidade_equipamento_emprestado' => 'required|integer|min:0',
        ]);

        $evento = Sala::findOrFail($eventoId);

        $usuario = auth()->user();

        if (!$usuario->is_admin && $evento->usuario_cod_usuario !== $usuario->cod_usuario) {
            abort(403, 'Você não tem permissão para modificar os equipamentos deste evento');
        }

        $evento->equipamentos()->updateExistingPivot($equipamentoId, [
            'quantidade_equipamento_emprestado' => $request->quantidade_equipamento_emprestado
        ]);

        return response()->json(['message' => 'Quantidade atualizada com sucesso'], 200);
    }

    public function destroy($eventoId, $equipamentoId){
        $evento = Evento::findOrFail($eventoId);
        
        $usuario = auth()->user();

        if (!$usuario->is_admin && $evento->usuario_cod_usuario !== $usuario->cod_usuario) {
            abort(403, 'Você não tem permissão para modificar os equipamentos deste evento');
        }
        
        $evento->equipamentos()->detach($equipamentoId);

        return response()->json(['message' => 'Equipamento removido com sucesso'], 200);
    }
}