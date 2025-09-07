<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Devuelve todo lo necesario para el dashboard:
 * - KPIs del periodo
 * - Series por día (ingresos / descuentos)
 * - Próximas de hoy
 * - Agenda semanal (eventos con posición por minutos)
 *
 * Parámetros (opcionales):
 *   range = today | week | month | custom   (por defecto: week)
 *   from=YYYY-MM-DD  to=YYYY-MM-DD          (obligatorios si range=custom)
 *   week_anchor=YYYY-MM-DD                  (lunes de la semana a mostrar en agenda; opcional)
 *
 * NOTA DE TZ:
 *   Se usa America/Mexico_City. Si tus timestamps están en UTC,
 *   usa convert_tz en los groupBy o configura la conexión para convertirlos.
 */
class DashboardController extends Controller
{
    private string $tz = 'America/Mexico_City';

    // Horario por día para capacidad semanal (puedes moverlo a config si quieres)
    private array $businessHours = [
        1 => ['open' => '08:00', 'close' => '18:00'], // Lunes
        2 => ['open' => '08:00', 'close' => '18:00'],
        3 => ['open' => '08:00', 'close' => '18:00'],
        4 => ['open' => '08:00', 'close' => '18:00'],
        5 => ['open' => '08:00', 'close' => '18:00'],
        6 => ['open' => '09:00', 'close' => '14:00'], // Sábado
        0 => ['open' => '09:00', 'close' => '14:00'],  // Domingo abierto
                                    // Domingo cerrado
    ];

    public function view()
    {
        return view('dashboard'); // resources/views/dashboard.blade.php
    }

    public function data(Request $request)
    {
        $userId = Auth::id();

        // ----- 1) Rango del período (por defecto: semana actual CDMX)
        $range = $request->string('range')->lower()->value() ?: 'week';

        $now = Carbon::now($this->tz);
        if ($range === 'today') {
            $from = $now->copy()->startOfDay();
            $to   = $now->copy()->endOfDay();
        } elseif ($range === 'month') {
            $from = $now->copy()->startOfMonth();
            $to   = $now->copy()->endOfMonth();
        } elseif ($range === 'custom') {
            $from = Carbon::parse($request->input('from'), $this->tz)->startOfDay();
            $to   = Carbon::parse($request->input('to'),   $this->tz)->endOfDay();
        } else { // week
            $from = $this->startOfWeek($now)->startOfDay();
            $to   = $this->endOfWeek($now)->endOfDay();
        }

        // ----- 2) Subquery con totales por cita (base, desc_linea, subtotal, desc_orden_aplicado, neto)
        $lines = DB::table('cita_servicio')
            ->select([
                'cita_id',
                DB::raw('SUM(cantidad * precio_servicio_snapshot) as base'),
                DB::raw('SUM(descuento) as desc_linea'),
            ])
            ->where('usuario_id', $userId)
            ->groupBy('cita_id');

        $citasPeriodo = DB::table('citas as ci')
            ->leftJoinSub($lines, 'li', fn($j) => $j->on('li.cita_id', '=', 'ci.id'))
            ->where('ci.usuario_id', $userId)
            ->whereBetween('ci.hora_inicio', [$from->toDateTimeString(), $to->toDateTimeString()])
            ->select([
                'ci.id','ci.cliente_id','ci.hora_inicio','ci.hora_fin',
                DB::raw('COALESCE(li.base,0) as base'),
                DB::raw('COALESCE(li.desc_linea,0) as desc_linea'),
                DB::raw('GREATEST(0, COALESCE(li.base,0) - COALESCE(li.desc_linea,0)) as subtotal'),
                DB::raw('COALESCE(ci.descuento,0) as desc_orden'),
                DB::raw('LEAST(GREATEST(0, COALESCE(li.base,0) - COALESCE(li.desc_linea,0)),
                               COALESCE(ci.descuento,0)) as desc_orden_aplicado'),
                DB::raw('GREATEST(0,
                      GREATEST(0, COALESCE(li.base,0) - COALESCE(li.desc_linea,0))
                      - LEAST(GREATEST(0, COALESCE(li.base,0) - COALESCE(li.desc_linea,0)),
                              COALESCE(ci.descuento,0))) as neto'),
            ])
            ->get();

        // Totales del período
        $kpi = [
            'bruto'            => (int) round($citasPeriodo->sum('base')),
            'descLineas'       => (int) round($citasPeriodo->sum('desc_linea')),
            'descOrden'        => (int) round($citasPeriodo->sum('desc_orden_aplicado')),
            'descTotal'        => 0, // se llena abajo
            'neto'             => (int) round($citasPeriodo->sum('neto')),
            'citasHoy'         => 0, // se llena abajo
            'clientesPeriodo'  => $citasPeriodo->pluck('cliente_id')->filter()->unique()->count(),
        ];
        $kpi['descTotal'] = $kpi['descLineas'] + $kpi['descOrden'];

        // Series por día (ingresos/desc) en el período
        $serie = $this->bucketByDay($citasPeriodo, $from, $to);

        // Próximas de hoy (ordenadas)
        [$todayStart, $todayEnd] = [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
        $hoy = DB::table('citas as ci')
            ->leftJoin('clientes as cl', 'cl.id', '=', 'ci.cliente_id')
            ->where('ci.usuario_id', $userId)
            ->whereBetween('ci.hora_inicio', [$todayStart->toDateTimeString(), $todayEnd->toDateTimeString()])
            ->orderBy('ci.hora_inicio')
            ->select('ci.id','ci.hora_inicio','ci.hora_fin','cl.nombre as cliente_nombre')
            ->get()
            ->map(function ($r) {
                return [
                    'id'           => (int) $r->id,
                    'hora_inicio'  => (string) $r->hora_inicio,
                    'hora_fin'     => (string) $r->hora_fin,
                    'cliente'      => ['nombre' => $r->cliente_nombre ?: 'Cliente'],
                ];
            })
            ->values();

        $kpi['citasHoy'] = $hoy->count();

        // Agenda semanal (lunes-domingo) anclada
        $weekAnchor = $request->filled('week_anchor')
            ? Carbon::parse($request->input('week_anchor'), $this->tz)
            : $now;
        $semStart = $this->startOfWeek($weekAnchor)->startOfDay();
        $semEnd   = $this->endOfWeek($weekAnchor)->endOfDay();

        $weekCitas = DB::table('citas as ci')
            ->leftJoin('clientes as cl', 'cl.id', '=', 'ci.cliente_id')
            ->where('ci.usuario_id', $userId)
            ->whereBetween('ci.hora_inicio', [$semStart->toDateTimeString(), $semEnd->toDateTimeString()])
            ->orderBy('ci.hora_inicio')
            ->select('ci.id','ci.hora_inicio','ci.hora_fin','cl.id as cliente_id','cl.nombre as cliente_nombre')
            ->get();

        // minutos bloqueados y capacidad semanal
        $bookedMinutes = $this->bookedMinutes($weekCitas, $userId);
        $capacityMinutes = $this->weekCapacityMinutes($semStart);

        // Ocupación del período seleccionado (si range=week la verás igual que gauge)
        $ocupacionPct = $capacityMinutes > 0 ? (int) round(($bookedMinutes / $capacityMinutes) * 100) : 0;

        return response()->json([
            'period' => [
                'range' => $range,
                'from'  => $from->toDateString(),
                'to'    => $to->toDateString(),
                'tz'    => $this->tz,
            ],
            'kpi' => [
                'bruto'           => $kpi['bruto'],
                'desc_linea'      => $kpi['descLineas'],
                'desc_orden'      => $kpi['descOrden'],
                'desc_total'      => $kpi['descTotal'],
                'neto'            => $kpi['neto'],
                'citas_hoy'       => $kpi['citasHoy'],
                'clientes_periodo'=> $kpi['clientesPeriodo'],
                'ocupacion_pct'   => $ocupacionPct,
            ],
            'series' => [
                'ingresos_por_dia' => $serie, // [{date, bruto, descuentos, neto}]
            ],
            'hoy' => $hoy, // [{id, hora_inicio, hora_fin, cliente:{nombre}}]
            'semana' => [
                'start'            => $semStart->toDateString(),
                'end'              => $semEnd->toDateString(),
                'capacity_minutes' => $capacityMinutes,
                'booked_minutes'   => $bookedMinutes,
                // eventos “simples”: el frontend puede posicionarlos en el grid
                'eventos'          => $weekCitas->map(fn($c) => [
                    'id'          => (int) $c->id,
                    'hora_inicio' => (string) $c->hora_inicio,
                    'hora_fin'    => (string) $c->hora_fin,
                    'cliente'     => ['id' => (int) $c->cliente_id, 'nombre' => $c->cliente_nombre ?: 'Cliente'],
                ])->values(),
            ],
        ], 200);
    }

    /* ----------------- Helpers ----------------- */

    private function startOfWeek(Carbon $d): Carbon
    {
        // lunes como inicio de semana
        return $d->copy()->startOfWeek(Carbon::MONDAY)->timezone($this->tz);
    }
    private function endOfWeek(Carbon $d): Carbon
    {
        return $d->copy()->endOfWeek(Carbon::SUNDAY)->timezone($this->tz);
    }

    /**
     * Devuelve [{date:'YYYY-MM-DD', bruto, descuentos, neto}] incluyendo días sin datos.
     */
    private function bucketByDay($citas, Carbon $from, Carbon $to): array
    {
        // Indexar por fecha
        $map = [];
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $map[$d->toDateString()] = ['date' => $d->toDateString(), 'bruto' => 0, 'descuentos' => 0, 'neto' => 0];
        }

        foreach ($citas as $c) {
            $date = Carbon::parse($c->hora_inicio, $this->tz)->toDateString();
            if (!isset($map[$date])) {
                $map[$date] = ['date' => $date, 'bruto' => 0, 'descuentos' => 0, 'neto' => 0];
            }
            $base       = (float) $c->base;
            $descLinea  = (float) $c->desc_linea;
            $subtotal   = max(0, $base - $descLinea);
            $descOrdenA = min($subtotal, (float) $c->desc_orden); // aplicado
            $neto       = max(0, $subtotal - $descOrdenA);

            $map[$date]['bruto']      += $base;
            $map[$date]['descuentos'] += ($descLinea + $descOrdenA);
            $map[$date]['neto']       += $neto;
        }

        return array_values($map);
    }

    /**
     * Minutos ocupados de una semana. Si la cita no tiene hora_fin, intenta
     * estimar desde cita_servicio (suma de duraciones*cantidad).
     */
    private function bookedMinutes($weekCitas, int $userId): int
    {
        $ids = $weekCitas->pluck('id')->all();
        if (empty($ids)) return 0;

        // Duración por cita desde items
        $durByCita = DB::table('cita_servicio')
            ->select('cita_id', DB::raw('SUM(duracion_minutos_snapshot * GREATEST(1, cantidad)) as mins'))
            ->where('usuario_id', $userId)
            ->whereIn('cita_id', $ids)
            ->groupBy('cita_id')
            ->pluck('mins', 'cita_id');

        $total = 0;
        foreach ($weekCitas as $c) {
            $start = Carbon::parse($c->hora_inicio, $this->tz);
            if ($c->hora_fin) {
                $end = Carbon::parse($c->hora_fin, $this->tz);
                $total += max(0, $end->diffInMinutes($start));
            } else {
                $mins = (int) ($durByCita[$c->id] ?? 0);
                $total += max(0, $mins);
            }
        }
        return $total;
    }

    /**
     * Capacidad semanal en minutos según $businessHours y la semana de $weekStart.
     */
    private function weekCapacityMinutes(Carbon $weekStart): int
    {
        $sum = 0;
        // Lunes (1) a Domingo (0) según $businessHours
        for ($i=0; $i<7; $i++) {
            $dow = ($weekStart->copy()->addDays($i)->dayOfWeek); // 0..6
            $bh = $this->businessHours[$dow] ?? null;
            if (!$bh) continue;
            [$oh,$om] = array_map('intval', explode(':', $bh['open']));
            [$ch,$cm] = array_map('intval', explode(':', $bh['close']));
            $sum += (($ch*60 + $cm) - ($oh*60 + $om));
        }
        return max(0, $sum);
    }
}
