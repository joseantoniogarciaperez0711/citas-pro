<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\CitaServicio;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $userId = Auth::id();

        // Servicios activos con nombre de empleado
        $servicios = Servicio::where('servicios.usuario_id', $userId)
            ->where('servicios.activo', 1)
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

        $categorias = Categoria::where('usuario_id', $userId)
            ->orderBy('orden')
            ->get(['id', 'nombre', 'descripcion', 'color', 'orden', 'activo']);

        $empleados = Empleado::where('usuario_id', $userId)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'activo', 'color', 'status']);

        $clientes = Cliente::where('usuario_id', $userId)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'telefono', 'correo', 'activo']);

        // pasa el id si vino en la URL (o null si no)
        $clienteSeleccionadoId = $id ? (int)$id : null;

        return view('menu.citas', compact(
            'servicios',
            'categorias',
            'empleados',
            'clientes',
            'clienteSeleccionadoId'
        ));
    }

    public function lista()
    {
        $userId = Auth::id();

        $citas = Cita::with(['cliente:id,nombre', 'items'])
            ->where('usuario_id', $userId)
            ->latest('hora_inicio')
            ->get();

        return response()->json(['citas' => $citas], 200);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'cliente_id'         => ['required', 'integer', 'exists:clientes,id'],
            'fecha'              => ['required', 'date'],
            'hora_inicio'        => ['required', 'date_format:H:i'],
            'notas'              => ['nullable', 'string'],
            'descuento_mxn'      => ['nullable', 'numeric', 'min:0'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.servicio_id'     => ['required', 'integer', 'exists:servicios,id'],
            'items.*.empleado_id'     => ['nullable', 'integer', 'exists:empleados,id'],
            'items.*.cantidad'        => ['nullable', 'integer', 'min:1'],
            'items.*.descuento_mxn'   => ['nullable', 'numeric', 'min:0'],
            'items.*.precio_unit'     => ['nullable', 'numeric', 'min:0'],
            'items.*.duracion_minutos' => ['nullable', 'integer', 'min:1'],
        ]);

        // tenant checks
        $clienteOk = Cliente::where('id', $validated['cliente_id'])
            ->where('usuario_id', $userId)->exists();
        if (!$clienteOk) return response()->json(['error' => 'Cliente no válido'], 422);

        $empleadoIds = collect($validated['items'])->pluck('empleado_id')->filter()->unique()->all();
        if ($empleadoIds) {
            $count = Empleado::where('usuario_id', $userId)->whereIn('id', $empleadoIds)->count();
            if ($count !== count($empleadoIds)) return response()->json(['error' => 'Empleado no válido'], 422);
        }

        // Combinar fecha y hora
        $fechaHora = Carbon::createFromFormat('Y-m-d H:i', $validated['fecha'] . ' ' . $validated['hora_inicio']);

        return DB::transaction(function () use ($validated, $fechaHora, $userId) {
            $total = 0;
            $totalMin = 0;
            $itemsToInsert = [];

            foreach ($validated['items'] as $i => $it) {
                $serv = Servicio::where('usuario_id', $userId)->findOrFail($it['servicio_id']);

                // Usar precio del frontend si viene, sino del servicio
                $precio = isset($it['precio_unit']) ? (float) $it['precio_unit'] : (float) $serv->precio;
                $cantidad = max(1, (int)($it['cantidad'] ?? 1));
                $descLinea = (float)($it['descuento_mxn'] ?? 0);
                $duracion = isset($it['duracion_minutos']) ? (int) $it['duracion_minutos'] : (int) $serv->duracion_minutos;

                $lineBase = $precio * $cantidad;
                $lineTotal = max(0, $lineBase - $descLinea);

                $total += $lineTotal;
                $totalMin += ($duracion * $cantidad);

                $itemsToInsert[] = [
                    'usuario_id'                => $userId,
                    'servicio_id'               => $serv->id,
                    'empleado_id'               => $it['empleado_id'] ?? null,
                    'nombre_servicio_snapshot'  => $serv->nombre,
                    'precio_servicio_snapshot'  => $precio,
                    'duracion_minutos_snapshot' => $duracion,
                    'cantidad'                  => $cantidad,
                    'descuento'                 => $descLinea,
                    'orden'                     => $i + 1,
                    'estado'                    => 'pendiente',
                    'notas'                     => $it['notas'] ?? null,
                    'creado_en'                 => now(),
                    'actualizado_en'            => now(),
                ];
            }

            $descuentoCita = (float)($validated['descuento_mxn'] ?? 0);
            $total = max(0, $total - $descuentoCita);

            $cita = Cita::create([
                'usuario_id'     => $userId,
                'cliente_id'     => $validated['cliente_id'],
                'fecha'          => $fechaHora->copy()->startOfDay(),
                'hora_inicio'    => $fechaHora,
                'hora_fin'       => (clone $fechaHora)->addMinutes($totalMin),
                'estado'         => 'pendiente',
                'notas'          => $validated['notas'] ?? null,
                'descuento'      => $descuentoCita,
                'total_snapshot' => $total,
            ]);

            foreach ($itemsToInsert as $row) {
                $row['cita_id'] = $cita->id;
                CitaServicio::create($row);
            }

            $cita->load(['cliente:id,nombre', 'items']);

            return response()->json([
                'message' => 'Cita creada',
                'cita'    => $cita
            ], 201);
        });
    }

    public function porCliente(\App\Models\Cliente $cliente)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        if ($cliente->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Trae citas del cliente con items y empleado (si hay)
        $citas = \App\Models\Cita::with([
            'items:id,cita_id,empleado_id,nombre_servicio_snapshot,precio_servicio_snapshot,duracion_minutos_snapshot,cantidad,descuento,orden',
            'items.empleado:id,nombre',
        ])
            ->where('usuario_id', $userId)
            ->where('cliente_id', $cliente->id)
            ->orderByDesc('hora_inicio')
            ->get(['id', 'usuario_id', 'cliente_id', 'hora_inicio', 'hora_fin', 'estado', 'notas', 'descuento', 'total_snapshot']);

        // Mapea con totales claros y labels listos
        $tz = 'America/Mexico_City';
        $out = $citas->map(function ($c) use ($tz) {
            $base = 0;
            $descLineas = 0;
            $mins = 0;

            foreach ($c->items as $it) {
                $base       += (int)$it->precio_servicio_snapshot * max(1, (int)$it->cantidad);
                $descLineas += (int)$it->descuento;
                $mins       += (int)$it->duracion_minutos_snapshot * max(1, (int)$it->cantidad);
            }
            $subtotal  = max(0, $base - $descLineas);
            $descOrden = min($subtotal, (int)$c->descuento);
            $neto      = max(0, $subtotal - $descOrden);

            $start = \Carbon\Carbon::parse($c->hora_inicio, $tz);
            $end   = $c->hora_fin ? \Carbon\Carbon::parse($c->hora_fin, $tz) : (clone $start)->addMinutes($mins);
            $mins  = max($mins, $end->diffInMinutes($start));

            $durH = intdiv($mins, 60);
            $durM = $mins % 60;
            $durLabel = ($durH ? "{$durH}h " : '') . "{$durM}m";

            return [
                'id'          => (int)$c->id,
                'estado'      => (string)$c->estado,
                'notas'       => (string)($c->notas ?? ''),
                'fecha_larga' => $start->isoFormat('ddd, DD MMM YYYY'),
                'hora_rango'  => $start->isoFormat('HH:mm') . ' – ' . $end->isoFormat('HH:mm'),
                'duracion_label' => $durLabel,
                'items' => $c->items->map(function ($it) {
                    return [
                        'id'                          => (int)$it->id,
                        'nombre_servicio_snapshot'    => $it->nombre_servicio_snapshot,
                        'precio_servicio_snapshot'    => (int)$it->precio_servicio_snapshot,
                        'duracion_minutos_snapshot'   => (int)$it->duracion_minutos_snapshot,
                        'cantidad'                    => (int)$it->cantidad,
                        'descuento'                   => (int)$it->descuento,
                        'empleado_nombre'             => optional($it->empleado)->nombre,
                    ];
                })->values(),
                'totales' => [
                    'bruto'       => (int)$base,
                    'desc_lineas' => (int)$descLineas,
                    'subtotal'    => (int)$subtotal,
                    'desc_orden'  => (int)$descOrden,
                    'neto'        => (int)$neto,
                ],
            ];
        })->values();

        return response()->json(['citas' => $out], 200);
    }

    public function update(Request $request, \App\Models\Cita $cita)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        if ($cita->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'fecha'       => ['nullable', 'date'],
            'hora_inicio' => ['nullable', 'date_format:H:i'],
            'estado'      => ['nullable', 'string', 'in:pendiente,reprogramada,terminada,cancelada'],
            'notas'       => ['nullable', 'string'],
        ]);

        // Si reprograma (fecha/hora), recalcula hora_fin basado en items
        if (!empty($data['fecha']) || !empty($data['hora_inicio'])) {
            // Combinar: si falta uno, usa el valor actual
            $fecha = !empty($data['fecha'])
                ? \Carbon\Carbon::createFromFormat('Y-m-d', $data['fecha'], 'America/Mexico_City')
                : \Carbon\Carbon::parse($cita->hora_inicio, 'America/Mexico_City');

            $horaStr = !empty($data['hora_inicio'])
                ? $data['hora_inicio']
                : \Carbon\Carbon::parse($cita->hora_inicio, 'America/Mexico_City')->isoFormat('HH:mm');

            $start = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $fecha->toDateString() . ' ' . $horaStr, 'America/Mexico_City');

            // Suma de duraciones*cantidad desde cita_servicio
            $mins = \Illuminate\Support\Facades\DB::table('cita_servicio')
                ->where('usuario_id', $userId)
                ->where('cita_id', $cita->id)
                ->selectRaw('COALESCE(SUM(duracion_minutos_snapshot * GREATEST(1,cantidad)),0) as mins')
                ->value('mins');

            $end = (clone $start)->addMinutes((int)$mins);

            $cita->fecha = $start->copy()->startOfDay();
            $cita->hora_inicio = $start;
            $cita->hora_fin = $end;
        }

        if (!empty($data['estado'])) {
            $cita->estado = $data['estado'];
        }
        if (array_key_exists('notas', $data)) {
            $cita->notas = $data['notas'];
        }

        $cita->save();

        return response()->json(['message' => 'Cita actualizada', 'cita' => $cita->fresh('cliente', 'items')], 200);
    }

    public function estado(Request $request, \App\Models\Cita $cita)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        if ($cita->usuario_id !== $userId) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'estado' => ['required', 'string', 'in:pendiente,terminada,cancelada,reprogramada'],
        ]);

        $cita->estado = $data['estado'];
        $cita->save();

        return response()->json(['message' => 'Estado actualizado', 'cita' => $cita], 200);
    }
}
