<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersKey;
use Illuminate\Support\Facades\Response;

class CheckTokenAndSecret
{
    public function handle(Request $request, Closure $next)
    {
        // Pegue o token e secret do corpo da requisição
        $token = $request->input('token');
        $secret = $request->input('secret');

        // Verifique se ambos os parâmetros token e secret foram enviados
        if (!$token || !$secret) {
            return Response::json([
                'error' => 'Token ou Secret ausentes',
                'message' => 'Você precisa fornecer tanto o token quanto o secret.'
            ], 400); // Retorna um erro 400 se os parâmetros não forem fornecidos
        }

        // Verifique se existe um usuário com esse token e secret
        $chaves = UsersKey::where('token', $token)->where('secret', $secret)->first();
//dd($token, $secret, $chaves);
        // Se o usuário não for encontrado, retorna um erro
        if (!$chaves) {
            return Response::json([
                'status' => "error",
                'message' => 'Token ou Secret inválidos'
            ], 401); // Retorna um erro 401 se o token ou secret não forem válidos
        }

        $user = User::where('username', $chaves->user_id)->first();
        
        // Verificar apenas status básico (removendo controle de banimento externo)
        if($user->status != 1){
            return Response::json([
                'status' => "error",
                'message' => 'Usuário com conta pendente de aprovação.'
            ], 401);
        }
        //dd($user);
        // Se o usuário for encontrado, adicione o usuário à requisição
        $request->merge(['user' => $user]);

        // Prossiga com a requisição
        return $next($request);
    }
}
