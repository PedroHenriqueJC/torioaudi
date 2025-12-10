<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventoController extends Controller
{   
    
    // POST INSERT
    
    public function store(Request $request){

        // obter usuário autenticado a partir do token
        $usuario = auth()->user();
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        $validated = $request->validate([
            'evento_inicio' => 'required|date',
            'evento_fim' => 'required|date|after_or_equal:evento_inicio',
            'nome_evento' => 'required|string|max:255',
            'descricao_evento' => 'nullable|string',
            'pre_agenda_evento' => 'boolean',
            'sala_cod_sala' => 'required|integer|exists:sala,cod_sala',
        ]);

        $conflito = Evento::where('sala_cod_sala', $validated['sala_cod_sala'])
            ->where(function ($q) use ($validated) {
                $q->where('evento_inicio', '<', $validated['evento_fim'])
                ->where('evento_fim', '>', $validated['evento_inicio']);
            })
            ->exists();

        if ($conflito) {
            return response()->json([
                'message' => 'Já existe um evento nessa sala nesse intervalo de tempo.'
            ], 422);
        }


        $validated['usuario_cod_usuario'] = $usuario->cod_usuario;

        $evento = Evento::create($validated);

        return response()->json([
            'message' => 'Evento criado com sucesso!',
            'evento' => $evento
        ], 201);
    }

    // PUT UPDATE

    public function update(Request $request, $id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        $usuario = auth()->user();

        if ($usuario->cod_usuario !== $evento->usuario_cod_usuario && !$usuario->is_admin) {
            return response()->json(['message' => 'Você não tem permissão para cancelar este evento'], 403);
        }

        $validated = $request->validate([
            'evento_inicio' => 'sometimes|date',
            'evento_fim' => 'sometimes|date|after_or_equal:evento_inicio',
            'nome_evento' => 'sometimes|string|max:255',
            'descricao_evento' => 'nullable|string',
            'pre_agenda_evento' => 'boolean',
            'sala_cod_sala' => 'sometimes|integer|exists:sala,cod_sala',
        ]);

        $dadosFinais = [
            'evento_inicio' => $validated['evento_inicio'] ?? $evento->evento_inicio,
            'evento_fim'    => $validated['evento_fim'] ?? $evento->evento_fim,
            'sala_cod_sala' => $validated['sala_cod_sala'] ?? $evento->sala_cod_sala,
        ];

        $conflito = Evento::where('sala_cod_sala', $dadosFinais['sala_cod_sala'])
            ->where('cod_evento', '!=', $evento->cod_evento)
            ->where(function ($q) use ($dadosFinais) {
                $q->where('evento_inicio', '<', $dadosFinais['evento_fim'])
                ->where('evento_fim', '>', $dadosFinais['evento_inicio']);
            })
            ->exists();

        if ($conflito) {
            return response()->json([
                'message' => 'Já existe um evento nessa sala nesse intervalo de tempo.'
            ], 422);
        }

        $validated['usuario_cod_usuario'] = $usuario->cod_usuario;

        $evento->update($validated);

        return response()->json([
            'message' => 'Evento atualizado com sucesso!',
            'evento' => $evento
        ], 200);
    }

    // DELETE DESTROY

    public function destroy($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        $usuario = auth()->user();

        if ($usuario->cod_usuario !== $evento->usuario_cod_usuario && !$usuario->is_admin) {
            return response()->json(['message' => 'Você não tem permissão para cancelar este evento'], 403);
        }

        if ($evento->trashed()) {
            return response()->json(['message' => 'Evento já está cancelado'], 400);
        }


        $evento->delete();

        return response()->json([
            'message' => 'Evento cancelado com sucesso!',
            'evento' => $evento
        ], 200);
    }

    // GET BY USUARIO

    public function getByUsuario($usuarioId)
    {
        $eventos = Evento::with(['usuario', 'sala', 'equipamentos'])
            ->where('usuario_cod_usuario', $usuarioId)
            ->get();

        if ($eventos->isEmpty()) {
            return response()->json(['message' => 'Nenhum evento encontrado para este usuário'], 404);
        }

        return response()->json($eventos, 200);
    }

    // GET BY SALA

    public function getBySala($salaId)
    {
        $eventos = Evento::with(['usuario', 'sala', 'equipamentos'])
            ->where('sala_cod_sala', $salaId)
            ->get();

        if ($eventos->isEmpty()) {
            return response()->json(['message' => 'Nenhum evento encontrado para esta sala'], 404);
        }

        return response()->json($eventos, 200);
    }

    // GET ALL

    public function index()
    {
        $eventos = Evento::with(['usuario', 'sala', 'equipamentos'])->get();
        return response()->json($eventos, 200);
    }

    // GET ONE

    public function show($id)
    {
        $evento = Evento::with(['usuario', 'sala', 'equipamentos'])->find($id);

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        return response()->json($evento, 200);
    }

    public function getByDia($ano, $mes, $dia)
    {
        $eventos = Evento::with(['usuario', 'sala', 'equipamentos'])
            ->whereDate('evento_inicio', '=', "$ano-$mes-$dia")
            ->get();

        return response()->json($eventos);
    }
}