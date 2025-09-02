{{-- resources/views/app/servicios.blade.php --}}
@php
  // Datos de ejemplo pensados para una ESTÉTICA si no llegan desde el controlador.
  $items = $items ?? [
    ['code' => 'CUT-DAMA', 'name' => 'Corte de dama',         'category' => 'Cabello',   'duration' => 45, 'price' => 250,  'stylist' => 'Ana'],
    ['code' => 'CUT-CAB',  'name' => 'Corte caballero',       'category' => 'Cabello',   'duration' => 30, 'price' => 180,  'stylist' => 'Luis'],
    ['code' => 'TINTE',    'name' => 'Coloración completa',   'category' => 'Cabello',   'duration' => 120,'price' => 950,  'stylist' => 'Sofía'],
    ['code' => 'MANI',     'name' => 'Manicure clásico',      'category' => 'Uñas',      'duration' => 45, 'price' => 220,  'stylist' => 'Paola'],
    ['code' => 'PEDI',     'name' => 'Pedicure spa',          'category' => 'Uñas',      'duration' => 60, 'price' => 320,  'stylist' => 'Paola'],
    ['code' => 'KERAT',    'name' => 'Keratina / Alisado',    'category' => 'Cabello',   'duration' => 150,'price' => 1800, 'stylist' => 'Luis'],
    ['code' => 'DEP-CEJ',  'name' => 'Depilación de ceja',    'category' => 'Depilación','duration' => 15, 'price' => 120,  'stylist' => 'Ana'],
    ['code' => 'MAQ-EVE',  'name' => 'Maquillaje de evento',  'category' => 'Maquillaje','duration' => 90, 'price' => 950,  'stylist' => 'Sofía'],
  ];
@endphp

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
      {{ __('Servicios de Estética') }}
    </h2>
  </x-slot>

  {{-- ====== Tabla responsive (móvil + desktop) enfocada a estética ====== --}}
  <div
    x-data="tableSetup({ items: @js($items) })"
    x-init="$watch('q', () => resetPage()); $watch('cat', () => resetPage()); $watch('pro', () => resetPage()); $watch('perPage', () => resetPage())"
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-4"
  >
    {{-- Controles --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 p-4 sm:p-5 shadow">
      <div class="flex flex-col gap-4">
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
          <div class="sm:col-span-2">
            <label class="sr-only" for="q">Buscar</label>
            <input id="q" type="text" x-model="q" placeholder="Buscar servicio, profesional o código…"
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-inset focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>

          <div>
            <label class="sr-only" for="cat">Categoría</label>
            <select id="cat" x-model="cat"
                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-inset focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Todas las categorías</option>
              <template x-for="c in categories" :key="c">
                <option :value="c" x-text="c"></option>
              </template>
            </select>
          </div>

          <div>
            <label class="sr-only" for="pro">Profesional</label>
            <select id="pro" x-model="pro"
                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-inset focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Todo el equipo</option>
              <template x-for="s in stylists" :key="s">
                <option :value="s" x-text="s"></option>
              </template>
            </select>
          </div>

          <div>
            <label class="sr-only" for="perPage">Por página</label>
            <select id="perPage" x-model.number="perPage"
                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-inset focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option :value="5">5</option>
              <option :value="8">8</option>
              <option :value="12">12</option>
              <option :value="20">20</option>
            </select>
          </div>
        </div>

        <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-400">
          <span>Total: <span class="font-semibold" x-text="filtered.length"></span> servicios</span>
          <span>Página <span class="font-semibold" x-text="page"></span>/<span class="font-semibold" x-text="pageCount"></span></span>
        </div>
      </div>
    </div>

    {{-- Versión móvil: cards --}}
    <div class="block md:hidden">
      <div class="space-y-3">
        <template x-if="paged.length === 0">
          <div class="border-2 border-dashed rounded-xl p-6 text-center text-slate-500 dark:text-slate-400">
            Sin resultados.
          </div>
        </template>

        <template x-for="it in paged" :key="it.code">
          <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
              <div>
                <div class="text-base font-semibold" x-text="it.name"></div>
                <div class="mt-1 flex flex-wrap items-center gap-2">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs border bg-slate-50 dark:bg-slate-800 border-slate-200/60 dark:border-slate-700" x-text="it.category"></span>
                  <span class="text-xs text-slate-500">Código: <span x-text="it.code"></span></span>
                  <span class="text-xs text-slate-500">Duración: <span x-text="it.duration"></span> min</span>
                  <span class="text-xs text-slate-500">Pro: <span x-text="it.stylist"></span></span>
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm text-slate-500">Desde</div>
                <div class="text-lg font-bold text-emerald-600" x-text="currency(it.price)"></div>
              </div>
            </div>

            <div class="mt-3 flex gap-2">
              <button class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 text-sm font-medium">Agendar</button>
              <button class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 text-sm font-medium">Editar</button>
              <button class="px-3 py-2 rounded-lg border border-rose-200 text-rose-700 text-sm font-medium">Eliminar</button>
            </div>
          </div>
        </template>
      </div>

      {{-- Paginación móvil --}}
      <div class="mt-4 flex items-center justify-between">
        <button
          class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 text-sm font-medium disabled:opacity-50"
          :disabled="page<=1" @click="page = Math.max(1, page-1)">Anterior</button>
        <div class="text-sm text-slate-600 dark:text-slate-400">Página <span class="font-semibold" x-text="page"></span>/<span class="font-semibold" x-text="pageCount"></span></div>
        <button
          class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 text-sm font-medium disabled:opacity-50"
          :disabled="page>=pageCount" @click="page = Math.min(pageCount, page+1)">Siguiente</button>
      </div>
    </div>

    {{-- Versión desktop: tabla --}}
    <div class="hidden md:block bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200/60 dark:border-slate-800">
            <tr>
              <th class="text-left px-5 py-4 font-semibold cursor-pointer select-none" @click="setSort('code')">
                Código
                <span class="ml-1" x-show="sortKey==='code'" x-text="sortDir==='asc' ? '▲' : '▼'"></span>
              </th>
              <th class="text-left px-5 py-4 font-semibold cursor-pointer select-none" @click="setSort('name')">
                Servicio
                <span class="ml-1" x-show="sortKey==='name'" x-text="sortDir==='asc' ? '▲' : '▼'"></span>
              </th>
              <th class="text-left px-5 py-4 font-semibold cursor-pointer select-none" @click="setSort('category')">
                Categoría
                <span class="ml-1" x-show="sortKey==='category'" x-text="sortDir==='asc' ? '▲' : '▼'"></span>
              </th>
              <th class="text-left px-5 py-4 font-semibold cursor-pointer select-none" @click="setSort('duration')">
                Duración
                <span class="ml-1" x-show="sortKey==='duration'" x-text="sortDir==='asc' ? '▲' : '▼'"></span>
              </th>
              <th class="text-left px-5 py-4 font-semibold cursor-pointer select-none" @click="setSort('stylist')">
                Profesional
                <span class="ml-1" x-show="sortKey==='stylist'" x-text="sortDir==='asc' ? '▲' : '▼'"></span>
              </th>
              <th class="text-right px-5 py-4 font-semibold cursor-pointer select-none" @click="setSort('price')">
                Precio
                <span class="ml-1" x-show="sortKey==='price'" x-text="sortDir==='asc' ? '▲' : '▼'"></span>
              </th>
              <th class="text-right px-5 py-4 font-semibold">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <template x-if="paged.length === 0">
              <tr>
                <td colspan="7" class="px-5 py-8 text-center text-slate-500 dark:text-slate-400">Sin resultados</td>
              </tr>
            </template>

            <template x-for="it in paged" :key="it.code">
              <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-900/40">
                <td class="px-5 py-4 font-medium" x-text="it.code"></td>
                <td class="px-5 py-4 font-semibold" x-text="it.name"></td>
                <td class="px-5 py-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs border bg-slate-50 dark:bg-slate-800 border-slate-200/60 dark:border-slate-700"
                        x-text="it.category"></span>
                </td>
                <td class="px-5 py-4"><span x-text="it.duration"></span> min</td>
                <td class="px-5 py-4" x-text="it.stylist"></td>
                <td class="px-5 py-4 text-right font-bold text-emerald-600" x-text="currency(it.price)"></td>
                <td class="px-5 py-4 text-right">
                  <button class="px-3 py-1.5 rounded-lg border border-primary-200 text-primary-700 dark:text-primary-400 mr-2 text-sm font-medium">Agendar</button>
                  <button class="px-3 py-1.5 rounded-lg border border-slate-200/60 dark:border-slate-700 mr-2 text-sm font-medium">Editar</button>
                  <button class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 text-sm font-medium">Eliminar</button>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      {{-- Paginación desktop --}}
      <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-sm text-slate-600 dark:text-slate-400">
          Mostrando
          <span class="font-semibold" x-text="((page-1)*perPage)+1"></span> –
          <span class="font-semibold" x-text="Math.min(page*perPage, sorted.length)"></span>
          de <span class="font-semibold" x-text="sorted.length"></span>
        </div>
        <div class="flex items-center gap-2">
          <button class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 text-sm font-medium disabled:opacity-50"
                  :disabled="page<=1" @click="page = Math.max(1, page-1)">Anterior</button>
          <div class="text-sm px-2">Página <span class="font-semibold" x-text="page"></span>/<span class="font-semibold" x-text="pageCount"></span></div>
          <button class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 text-sm font-medium disabled:opacity-50"
                  :disabled="page>=pageCount" @click="page = Math.min(pageCount, page+1)">Siguiente</button>
        </div>
      </div>
    </div>
  </div>
  {{-- ====== /Tabla responsive estética ====== --}}

  <!-- Alpine helper -->
  <script>
    function tableSetup({ items }) {
      return {
        items,
        q: '',
        cat: '',
        pro: '',
        sortKey: 'name',
        sortDir: 'asc',
        perPage: 8,
        page: 1,
        get categories() {
          return Array.from(new Set(this.items.map(i => i.category))).sort();
        },
        get stylists() {
          return Array.from(new Set(this.items.map(i => i.stylist))).sort();
        },
        get filtered() {
          const q = this.q.trim().toLowerCase();
          return this.items.filter(i =>
            (!this.cat || i.category === this.cat) &&
            (!this.pro || i.stylist === this.pro) &&
            (!q || i.name.toLowerCase().includes(q)
               || i.stylist.toLowerCase().includes(q)
               || (i.code||'').toLowerCase().includes(q)
               || i.category.toLowerCase().includes(q))
          );
        },
        get sorted() {
          const dir = this.sortDir === 'asc' ? 1 : -1;
          const key = this.sortKey;
          return [...this.filtered].sort((a, b) => {
            let A = a[key], B = b[key];
            if (typeof A === 'string') { A = A.toLowerCase(); B = B.toLowerCase(); }
            return (A > B ? 1 : A < B ? -1 : 0) * dir;
          });
        },
        get pageCount() {
          return Math.max(1, Math.ceil(this.sorted.length / this.perPage));
        },
        get paged() {
          const start = (this.page - 1) * this.perPage;
          return this.sorted.slice(start, start + this.perPage);
        },
        setSort(key) {
          if (this.sortKey === key) this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
          else { this.sortKey = key; this.sortDir = 'asc'; }
        },
        resetPage() { this.page = 1; },
        currency(v) {
          try { return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(v); }
          catch { return '$' + Number(v).toFixed(2); }
        }
      }
    }
  </script>
</x-app-layout>

{{-- SOLO para pruebas/local (el proyecto real debe compilar Tailwind) --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    darkMode: 'class',
    theme: {
      extend: {
        colors: {
          primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' },
          accent:  { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95' }
        }
      }
    }
  }
</script>
