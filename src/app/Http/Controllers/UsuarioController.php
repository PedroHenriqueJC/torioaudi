<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nome_usuario' => 'required|string|max:255',
            'email_usuario' => 'required|string|email|unique:usuarios,email_usuario',
            'senha_usuario' => 'required|string|min:6',
        ]);

        $existe = User::withTrashed()->where('email_usuario', $request->email_usuario)->first();

        if($existe){
            $if($existe->delete_at){
                return response()->json(['message' => 'Seu usuário foi desativado. Por favor, entre em contato com um administrador.'], 403);
            }

            return response()->json(['message' => 'Email já cadastrado'], 422);
        }


        $usuario = Usuario::create([
            'nome_usuario' => $request->nome_usuario,
            'email_usuario' => $request->email_usuario,
            'senha_usuario' => Hash::make($request->senha_usuario),
            'role_usuario' => 0,
        ]);

        // Criar token para API
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_usuario' => 'required|email',
            'senha_usuario' => 'required|string'
        ]);

        $usuario = Usuario::withTrashed()->where('email_usuario', $request->email_usuario)->first();

        if (!$usuario || !Hash::check($request->senha_usuario, $usuario->senha_usuario)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        if($usuario->deleted_at){
            return response()->json(['message' => 'Seu usuário foi desativado. Por favor, entre em contato com um administrador.'], 403);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token' => $token
        ]);
    }

    public function index(Request $request){
        $usuario = $request->user();

        if(!$usuario->isAdmin()){
            return response()->json(['message' => 'Acesso negado!'], 403);
        }

        return response()->json(Usuario::all(), 200);
    }

    public function show(Request $request, $id){
        $usuario = $request->user();

        if(!$usuario->isAdmin() && $usuario->cod_usuario != $id){
            return response()->json(['message' => 'Acesso negado!'], 403);
        }

        $user = Usuario::find($id);

        if(!$user){
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($user, 200);
    }

    public function update(Request $request, $id){
        $usuario = $request->user();
        $user = Usuario::find($id);

        if(!$user){
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        if(!$usuario->isAdmin() && $usuario->cod_usuario != $id){
            return response()->json(['message' => 'Acesso negado!'], 403);
        }

        $request->validate([
            'nome_usuario' => 'sometimes|string|max:255',
            'senha_usuario' => 'sometimes|string|min:6',
            'role_usuario' => 'sometimes|integer'
        ]);
        $data = $request->only(['nome_usuario']);

        if($usuario->isAdmin() && $request->filled('role_usuario')){
            $data['role_usuario'] = $request->role_usuario;
        }

        if($request->filled('senha_usuario')){
            $data['senha_usuario'] = Hash::make($request->senha_usuario);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'usuario' => $user,
        ], 200);
    }

    public function destroy($id){
        $usuario = auth()->user();
        $user = Usuario::find($id);

        if(!$user){
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        if($usuario->cod_usuario === $user->cod_usuario){
            return response()->json(['message' => 'Você não pode deletar a si mesmo'], 403);
        }

        $user->delete(); // soft delete
        
        return response()->json(['message' => 'Usuário desativado com sucesso'], 200);
    }

    public function restore($id){
        $user = Usuario::withTreashed()->find($id);

        if(!$user){
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        if(!$user->deleted_at){
            return response()->json(['message' => 'Usuário já está ativo'], 400);
        }

        $user->restore();

        return response()->json([
            'message' => 'Usuário reativo com sucesso',
            'usuario' => $user,
        ], 200)
    }


    /**
     * Retorna os dados do usuário autenticado
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Logout do usuário (revoga token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}
