<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClienteArchivoController extends Controller
{
    public function index(Cliente $cliente)
    {
        $userId = Auth::id();
        if ($cliente->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $files = $cliente->archivos()
            ->orderByDesc('created_at')
            ->get(['id', 'cliente_id', 'titulo', 'nombre_original', 'mime', 'tamano', 'path', 'created_at']);

        return response()->json(['archivos' => $files], 200);
    }

    public function store(Request $request, Cliente $cliente)
    {
        $userId = Auth::id();
        if ($cliente->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'archivos'   => 'required',
            'archivos.*' => 'file|max:20480', // 20MB por archivo
            'titulos'    => 'array',          // opcional, mismo orden que archivos
        ]);

        $created = [];
        foreach ((array) $request->file('archivos', []) as $idx => $file) {
            $path = $file->store("clientes/{$userId}/{$cliente->id}", 'public');

            $created[] = ClienteArchivo::create([
                'usuario_id'      => $userId,
                'cliente_id'      => $cliente->id,
                'titulo'          => $request->input("titulos.$idx") ?? null,
                'nombre_original' => $file->getClientOriginalName(),
                'mime'            => $file->getClientMimeType(),
                'tamano'          => $file->getSize(),
                'path'            => $path,
            ]);
        }

        return response()->json(['message' => 'Archivos subidos', 'archivos' => $created], 201);
    }

    public function destroy(ClienteArchivo $archivo)
    {
        $userId = Auth::id();
        if ($archivo->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        Storage::disk('public')->delete($archivo->path);
        $archivo->delete();

        return response()->json(['message' => 'Archivo eliminado'], 200);
    }

    public function download(ClienteArchivo $archivo)
    {
        $userId = Auth::id();
        if ($archivo->usuario_id !== $userId) {
            abort(403);
        }
        return Storage::disk('public')->download($archivo->path, $archivo->nombre_original);
    }
}
