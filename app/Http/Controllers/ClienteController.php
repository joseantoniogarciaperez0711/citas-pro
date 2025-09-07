<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $clientes = Cliente::where('usuario_id', $userId)
            ->withCount('archivos')
            ->orderBy('nombre')
            ->get(['id', 'usuario_id', 'nombre', 'telefono', 'correo', 'notas', 'activo', 'created_at', 'updated_at']);

        return response()->json(['clientes' => $clientes], 200);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo'   => 'nullable|email|max:255',
            'notas'    => 'nullable|string',
            'activo'   => 'boolean',
        ]);

        $cliente = Cliente::create([
            'usuario_id' => $userId,
            'nombre'     => $data['nombre'],
            'telefono'   => $data['telefono'] ?? null,
            'correo'     => $data['correo'] ?? null,
            'notas'      => $data['notas'] ?? null,
            'activo'     => $data['activo'] ?? true,
        ])->loadCount('archivos');

        return response()->json(['message' => 'Cliente creado', 'cliente' => $cliente], 201);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $userId = Auth::id();
        if ($cliente->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo'   => 'nullable|email|max:255',
            'notas'    => 'nullable|string',
            'activo'   => 'boolean',
        ]);

        $cliente->update([
            'nombre'   => $data['nombre'],
            'telefono' => $data['telefono'] ?? $cliente->telefono,
            'correo'   => $data['correo'] ?? $cliente->correo,
            'notas'    => $data['notas'] ?? $cliente->notas,
            'activo'   => $data['activo'] ?? $cliente->activo,
        ]);

        $cliente->loadCount('archivos');

        return response()->json(['message' => 'Cliente actualizado', 'cliente' => $cliente], 200);
    }

    // Desactiva (no borra)
    public function destroy(Cliente $cliente)
    {
        $userId = Auth::id();
        if ($cliente->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $cliente->update(['activo' => false]);

        return response()->json(['message' => 'Cliente desactivado', 'cliente' => $cliente->fresh()->loadCount('archivos')], 200);
    }

    public function restore(Cliente $cliente)
    {
        $userId = Auth::id();
        if ($cliente->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $cliente->update(['activo' => true]);

        return response()->json(['message' => 'Cliente reactivado', 'cliente' => $cliente->fresh()->loadCount('archivos')], 200);
    }
}
