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

        $evento->equipamentos()->updateExistingPivot($equipamentoId, [
            'quantidade_equipamento_emprestado' => $request->quantidade_equipamento_emprestado
        ]);

        return response()->json(['message' => 'Quantidade atualizada com sucesso'], 200);
    }

    public function destroy($eventoId, $equipamentoId){
        $evento = Evento::findOrFail($eventoId);
        $evento->equipamentos()->detach($equipamentoId);

        return response()->json(['message' => 'Equipamento removido com sucesso'], 200);
    }
}