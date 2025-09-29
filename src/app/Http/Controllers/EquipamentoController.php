<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use Illuminate\Http\Request;

class EquipamentoController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'nome_equipamento' => 'required|string|max:45',
            'descricao_equipamento' => 'nullable|string|max:255',
            'quantidade_equipamento' => 'required|integer|min:1',
        ]);

        $equipamento = Equipamento::create([
            'nome_equipamento' => $request->nome_equipamento,
            'descricao_equipamento' => $request->descricao_equipamento,
            'quantidade_equipamento' => $request->quantidade_equipamento,
        ]);

        return response()->json([
            'message' => 'Equipamento criado com sucesso!',
            'equipamento' => $equipamento
        ], 201);
    }

    public function index(Request $request){
        return response()->json(Equipamento::all(), 200);
    }

    public function show($id){
        $equipamento = Equipamento::find($id);
        if(!$equipamento){
            return response()->json(['message' = 'Equipamento não encontrado'], 404);
        }

        return response()->json($equipamento,200);
    }

    public function update(Request $request, $id){
        $equipamento = Equipamento::find($id);
        if(!$equipamento){
            return response()->json(['message' = 'Equipamento não encontrado'], 404);
        }

        $request->validate([
            'nome_equipamento' => 'required|string|max:45',
            'descricao_equipamento' => 'nullable|string|max:255',
            'quantidade_equipamento' => 'required|integer|min:1',
        ]);

        $equipamento->update($request->only(['nome_equipamento', 'descricao_equipamento', 'quantidade_equipamento']));
        return response()->json([
            'message' => 'Equipamento atualizado com sucesso!',
            'equipamento' => $equipamento,
        ], 200);
    }

    public function destroy($id){
        $equipamento = Equipamento::find($id);
        if(!$equipamento){
            return response()->json(['message' => 'Equipamento não encontrado'], 404);
        }
        $equipamento->delete();
        return response()->json(['message' => 'Equipamento deletado com sucesso'], 200);
    }
}