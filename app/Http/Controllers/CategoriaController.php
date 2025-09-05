<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;



class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/CategoriaController.php
    // app/Http/Controllers/CategoriaController.php
    public function index(Request $request)
    {
        $userId = Auth::id();

        $categorias = Categoria::where('usuario_id', $userId)
            ->withCount('servicios')             // ← añade servicios_count
            ->orderBy('orden')
            ->get(['id', 'nombre', 'descripcion', 'color', 'orden', 'activo']);

        return response()->json([
            'categorias' => $categorias
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'color'       => 'nullable|string|max:10',
            'orden'       => 'required|integer|min:1',
            'activo'      => 'boolean'
        ]);

        $categoria = Categoria::create([
            'usuario_id'  => $userId,
            'nombre'      => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'color'       => $validated['color'] ?? '#374151',
            'orden'       => $validated['orden'],
            'activo'      => $validated['activo'] ?? true,
        ]);

        $categoria->loadCount('servicios'); // ← tendrá 0

        return response()->json([
            'message'   => 'Categoría creada',
            'categoria' => $categoria
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $userId = Auth::id();
        if ($categoria->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'nombre'      => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias', 'nombre')
                    ->where(fn($q) => $q->where('usuario_id', $userId))
                    ->ignore($categoria->id),
            ],
            'descripcion' => 'nullable|string|max:500',
            'color'       => 'nullable|string|max:10',
            'orden'       => 'required|integer|min:1',
            'activo'      => 'boolean',
            'icono'       => 'nullable|string|max:255',
        ]);

        $categoria->update([
            'nombre'      => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'color'       => $validated['color'] ?? '#374151',
            'orden'       => $validated['orden'],
            'activo'      => $validated['activo'] ?? true,
            'icono'       => $validated['icono'] ?? null,
        ]);

        $categoria->loadCount('servicios');

        $categoria->loadCount('servicios'); // ← asegura servicios_count

        return response()->json([
            'message'   => 'Categoría actualizada',
            'categoria' => $categoria
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $userId = Auth::id();
        if ($categoria->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Bloquear eliminación si tiene servicios
        if ($categoria->servicios()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar: la categoría tiene servicios vinculados.'
            ], 409); // Conflict
        }

        $categoria->delete();

        return response()->json([
            'message' => 'Categoría eliminada'
        ], 200);
        // Si prefieres 204 sin body: return response()->noContent();
    }
}
