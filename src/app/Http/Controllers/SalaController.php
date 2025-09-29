<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nome_sala' => 'required|string|max:255',
            'capacidade_sala' => 'required|integer|min:1',
            'localizacao_sala' => 'nullable|string|max:255',
        ]);

        $sala = Sala::create([
            'nome_sala' => $request->nome_sala,
            'capacidade_sala' => $request->capacidade_sala,
            'localizacao_sala' => $request->localizacao_sala,
        ]);

        return response()->json([
            'message' => 'Sala criada com sucesso!',
            'sala' => $sala
        ], 201);
    }

    public function index(Request $request){
        return response()->json(Sala::all(), 200);
    }

    public function show($id){
        $sala = Sala::find($id);
        if(!$sala){
            return response()->json(['message' => 'Sala não encontrada'], 404);
        }

        return response()->json($sala, 200);
    }

    public function update(Request $request, $id){
        $sala = Sala::find($id);
        if(!$sala){
            return response()->json(['message' => 'Sala não encontrada'], 404);
        }

        $request->validate([
            'nome_sala' => 'sometimes|string|max:255',
            'capacidade_sala' => 'sometimes|integer|min:1',
            'localizacao_sala' => 'nullable|string|max:255',
        ]);

        $sala->update($request->only(['nome_sala', 'capacidade_sala', 'localizacao_sala']));
        return response()->json{[
            'message' => 'Sala atualizada com sucesso!',
            'sala' => $sala,
        ], 200};
    }

    public function destroy($id){
        $sala = Sala::find($id);
        if(!$sala){
            return response()->json(['message' => 'Sala não encontrada'], 404);
        }
        $sala->delete();
        return response()->json(['message' => 'Sala deletada com sucesso'], 200);
    }
}