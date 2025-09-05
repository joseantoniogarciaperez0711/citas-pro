<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Empleado;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // Servicios (con nombre del empleado si existe)
        $servicios = Servicio::where('servicios.usuario_id', $userId)
            ->leftJoin('empleados', 'servicios.profesional', '=', 'empleados.id')
            ->orderBy('servicios.orden')
            ->get([
                'servicios.id',
                'servicios.usuario_id',
                'servicios.categoria_id',
                'servicios.nombre',
                'servicios.duracion_minutos',
                'servicios.precio',
                'servicios.descripcion',
                'servicios.color',
                'servicios.profesional as empleado_id',
                'servicios.orden',
                'servicios.activo',
                'servicios.buffer_antes',
                'servicios.buffer_despues',
                'empleados.nombre as empleado_nombre',
            ]);

        // Empleados ACTIVOS del usuario
        $empleados = Empleado::where('usuario_id', $userId)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
                'puesto',
                'color',
                'status',
                'activo'
            ]);

        return response()->json([
            'servicios' => $servicios,
            'empleados' => $empleados,   // ← aquí van para la vista
        ], 200);
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
            'nombre'            => 'required|string|max:255',
            'categoria_id'      => [
                'required',
                'integer',
                Rule::exists('categorias', 'id')->where(fn($q) => $q->where('usuario_id', $userId)),
            ],
            'duracion_minutos'  => 'required|integer|min:5|max:1440',
            'precio'            => 'required|numeric|min:0',
            'descripcion'       => 'nullable|string|max:1000',
            'color'             => 'nullable|string|max:10',
            'empleado_id'       => [
                'nullable',
                'integer',
                Rule::exists('empleados', 'id')->where(fn($q) => $q->where('usuario_id', $userId)),
            ],
            'orden'             => 'nullable|integer|min:1',
            'activo'            => 'nullable|boolean',
            'buffer_antes'      => 'nullable|integer|min:0|max:240',
            'buffer_despues'    => 'nullable|integer|min:0|max:240',
        ]);

        $servicio = Servicio::create([
            'usuario_id'       => $userId,
            'categoria_id'     => $validated['categoria_id'],
            'nombre'           => $validated['nombre'],
            'duracion_minutos' => $validated['duracion_minutos'],
            'precio'           => $validated['precio'],
            'descripcion'      => $validated['descripcion'] ?? null,
            'color'            => $validated['color'] ?? null,
            'profesional'      => $validated['empleado_id'] ?? null, // mapea a tu columna
            'orden'            => $validated['orden'] ?? 1,
            'activo'           => $validated['activo'] ?? true,
            'buffer_antes'     => $validated['buffer_antes'] ?? 0,
            'buffer_despues'   => $validated['buffer_despues'] ?? 0,
        ]);

        return response()->json(['message' => 'Servicio creado', 'servicio' => $servicio], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Servicio $servicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servicio $servicio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servicio $servicio)
    {
        $userId = Auth::id();
        if ($servicio->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'nombre'            => 'required|string|max:255',
            'categoria_id'      => [
                'required',
                'integer',
                Rule::exists('categorias', 'id')->where(fn($q) => $q->where('usuario_id', $userId)),
            ],
            'duracion_minutos'  => 'required|integer|min:5|max:1440',
            'precio'            => 'required|numeric|min:0',
            'descripcion'       => 'nullable|string|max:1000',
            'color'             => 'nullable|string|max:10',
            'empleado_id'       => [
                'nullable',
                'integer',
                Rule::exists('empleados', 'id')->where(fn($q) => $q->where('usuario_id', $userId)),
            ],
            'orden'             => 'nullable|integer|min:1',
            'activo'            => 'nullable|boolean',
            'buffer_antes'      => 'nullable|integer|min:0|max:240',
            'buffer_despues'    => 'nullable|integer|min:0|max:240',
        ]);

        $servicio->update([
            'categoria_id'     => $validated['categoria_id'],
            'nombre'           => $validated['nombre'],
            'duracion_minutos' => $validated['duracion_minutos'],
            'precio'           => $validated['precio'],
            'descripcion'      => $validated['descripcion'] ?? null,
            'color'            => $validated['color'] ?? null,
            'profesional'      => $validated['empleado_id'] ?? null,
            'orden'            => $validated['orden'] ?? $servicio->orden,
            'activo'           => $validated['activo'] ?? $servicio->activo,
            'buffer_antes'     => $validated['buffer_antes'] ?? $servicio->buffer_antes,
            'buffer_despues'   => $validated['buffer_despues'] ?? $servicio->buffer_despues,
        ]);

        return response()->json(['message' => 'Servicio actualizado', 'servicio' => $servicio], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servicio $servicio)
    {
        $userId = Auth::id();
        if ($servicio->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $servicio->delete();
        return response()->json(['message' => 'Servicio eliminado'], 200);
    }
}
