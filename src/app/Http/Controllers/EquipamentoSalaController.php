<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Equipamento;
use Illuminate\Http\Request;

class EquipamentoSalaController extends Controller{
    public function showBySala($salaId){
        $sala = Sala::with('equipamentos')->findOrFail($salaId);
        return response()->json($sala->equipamentos, 200);
    }

    public function showByEquipamento($equipamentoId){
        $equipamento = Equipamento::with('salas')->findOrFail($equipamentoId);
        return response()->json($equipamento->salas, 200);
    }

    public function store(Request $request){
        $request->validate([
            "sala_cod_sala" => 'required|exists:sala,cod_sala',
            "equipamento_cod_equipamento" => 'required|exists:equipamento,cod_equipamento',
            "quantidade_equipamento" => 'required|integer|min:0',
        ]);

        $sala = Sala::findOrFail($request->sala_cod_sala);

        $sala->equipamentos()->syncWithoutDetaching([
            $request->equipamento_cod_equipamento => [
                'quantidade_equipamento' => $request->quantidade_equipamento
            ]
        ]);

        return response()->json(['message' => 'Equipamento adicionado ou atualizado com sucesso'], 200);
    }

    public function update(Request $request){
        $request->validate([
            'quantidade_equipamento' => 'required|integer|min:0',
        ]);

        $sala = Sala::findOrFail($salaId);

        $sala->equipamentos()->updateExistingPivot($equipamentoId, [
            'quantidade_equipamento' => $request->quantidade_equipamento
        ]);

        return response()->json(['message' => 'Quantidade atualizada com sucesso'], 200);
    }

    public function destroy($salaId, $salaEquipamento){
        $sala = Sala::findOrFail($salaId);
        $sala->equipamentos()->detach($equipamentoId);

        return response()->json(['message' => 'Equipamento removido com sucesso'], 200);
    }
}