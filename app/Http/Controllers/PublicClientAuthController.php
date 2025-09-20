<?php

namespace App\Http\Controllers;

use App\Models\StorefrontLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class PublicClientAuthController extends Controller
{
    public function check(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'phone' => ['required', 'digits:10'],
        ]);

        $link = StorefrontLink::query()
            ->where('token', $data['token'])
            ->whereNull('revocado_en')
            ->firstOrFail();

        $userId = $link->usuario_id;

        $cliente = DB::table('clientes')
            ->where('usuario_id', $userId)
            ->where('telefono', $data['phone'])
            ->where('activo', 1)
            ->first();

        if ($cliente) {
            // Asegura public_token
            if (empty($cliente->public_token)) {
                $newTok = (string) Str::uuid();
                DB::table('clientes')->where('id', $cliente->id)->update([
                    'public_token' => $newTok,
                    'updated_at'   => now(),
                ]);
                $cliente->public_token = $newTok;
            }

            return response()->json([
                'exists'       => true,
                'cliente'      => $cliente,
                // 👉 URL con 2 tokens
                'redirect_url' => route('tienda.publica', [
                    'bizToken'    => $data['token'],
                    'clientToken' => $cliente->public_token,
                ]),
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'token'    => ['required', 'string'],
            'nombre'   => ['required', 'string', 'min:2', 'max:120'],
            'telefono' => ['required', 'digits:10'],
            'correo'   => ['nullable', 'email', 'max:190'],
        ]);

        $link = StorefrontLink::query()
            ->where('token', $data['token'])
            ->whereNull('revocado_en')
            ->firstOrFail();

        $userId = $link->usuario_id;

        // Evita duplicado por teléfono
        $cliente = DB::table('clientes')
            ->where('usuario_id', $userId)
            ->where('telefono', $data['telefono'])
            ->first();

        if ($cliente) {
            // Asegura public_token
            if (empty($cliente->public_token)) {
                $newTok = (string) Str::uuid();
                DB::table('clientes')->where('id', $cliente->id)->update([
                    'public_token' => $newTok,
                    'updated_at'   => now(),
                ]);
                $cliente->public_token = $newTok;
            }

            return response()->json([
                'ok'           => true,
                'cliente'      => $cliente,
                'redirect_url' => route('tienda.publica', [
                    'bizToken'    => $data['token'],
                    'clientToken' => $cliente->public_token,
                ]),
            ]);
        }

        $clientToken = (string) Str::uuid();
        $id = DB::table('clientes')->insertGetId([
            'usuario_id'   => $userId,
            'nombre'       => $data['nombre'],
            'telefono'     => $data['telefono'],
            'correo'       => $data['correo'] ?? null,
            'public_token' => $clientToken,
            'activo'       => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $cliente = DB::table('clientes')->where('id', $id)->first();

        return response()->json([
            'ok'           => true,
            'cliente'      => $cliente,
            // 👉 URL con 2 tokens
            'redirect_url' => route('tienda.publica', [
                'bizToken'    => $data['token'],
                'clientToken' => $clientToken,
            ]),
        ], 201);
    }
}
