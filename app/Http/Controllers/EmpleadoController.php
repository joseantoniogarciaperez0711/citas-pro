<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // Trae conteo de servicios: relacion servicios() debe apuntar a foreign key 'profesional'
        $empleados = Empleado::where('usuario_id', $userId)
            ->withCount('servicios')
            ->orderBy('nombre')
            ->get(['id', 'usuario_id', 'nombre', 'puesto', 'color', 'status', 'activo', 'created_at', 'updated_at']);

        return response()->json(['empleados' => $empleados], 200);
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
            'nombre' => 'required|string|max:255',
            'puesto' => 'nullable|string|max:255',
            'color'  => 'nullable|string|max:10',
            'status' => 'nullable|string|max:50',
            'activo' => 'boolean',
        ]);

        $empleado = Empleado::create([
            'usuario_id' => $userId,
            'nombre'     => $validated['nombre'],
            'puesto'     => $validated['puesto'] ?? null,
            'color'      => $validated['color'] ?? '#8b5cf6',
            'status'     => $validated['status'] ?? null,
            'activo'     => $validated['activo'] ?? true,
        ]);

        $empleado->loadCount('servicios');

        return response()->json(['message' => 'Empleado creado', 'empleado' => $empleado], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado)
    {
        $userId = Auth::id();
        if ($empleado->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'puesto' => 'nullable|string|max:255',
            'color'  => 'nullable|string|max:10',
            'status' => 'nullable|string|max:50',
            'activo' => 'boolean',
        ]);

        $empleado->update([
            'nombre' => $validated['nombre'],
            'puesto' => $validated['puesto'] ?? null,
            'color'  => $validated['color'] ?? $empleado->color,
            'status' => $validated['status'] ?? $empleado->status,
            'activo' => $validated['activo'] ?? $empleado->activo,
        ]);

        $empleado->loadCount('servicios');

        return response()->json(['message' => 'Empleado actualizado', 'empleado' => $empleado], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // app/Http/Controllers/EmpleadoController.php

    // EmpleadoController.php

    public function destroy(Empleado $empleado)
    {
        $userId = Auth::id();
        if ($empleado->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Desactivar (soft delete lógico)
        $empleado->update([
            'activo' => false,
            'status' => $empleado->status ?: 'inactivo',
        ]);

        return response()->json([
            'message'  => 'Empleado desactivado',
            'empleado' => $empleado->fresh(),
        ], 200);
    }

    public function restore(Empleado $empleado)
    {
        $userId = Auth::id();
        if ($empleado->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $empleado->update([
            'activo' => true,
            'status' => $empleado->status === 'inactivo' ? null : $empleado->status,
        ]);

        return response()->json([
            'message'  => 'Empleado reactivado',
            'empleado' => $empleado->fresh(),
        ], 200);
    }
}
