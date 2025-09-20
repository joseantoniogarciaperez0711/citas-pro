<?php

namespace App\Http\Controllers;

use App\Models\StorefrontLink;   // o TiendaEnlace, según tu modelo
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiendaPublicaController extends Controller
{
    public function show(string $bizToken, ?string $clientToken = null)
    {
        $link = StorefrontLink::with('usuario')->activo()
            ->where('token', $bizToken)->firstOrFail();

        $usuario = $link->usuario;

        $cliente = null;
        if ($clientToken) {
            $cliente = \DB::table('clientes')
                ->where('usuario_id', $usuario->id)
                ->where('public_token', $clientToken)
                ->where('activo', 1)
                ->first();
        }

        $file    = $usuario->business_logo_filename ?? ($usuario->business_logo_path ? basename($usuario->business_logo_path) : null);
        $logoUrl = $file ? asset('storage/business-logos/' . $file) : null;

        return view('clientes.tienda', [
            'usuario'       => $usuario,
            'servicios'     => Servicio::where('usuario_id', $usuario->id)->where('activo', true)->orderBy('orden')->orderBy('nombre')->get(),
            'logoUrl'       => $logoUrl,
            'bizToken'      => $bizToken,
            'clientToken'   => $clientToken,
            'cliente'       => $cliente,                        // <-- por si lo usas en más sitios
            'clienteNombre' => $cliente->nombre ?? null,        // <-- ¡siempre definido!
        ]);
    }

    public function vistaTienda(string $bizToken)
    {
        // 404 si el token no existe o está revocado
        $link = StorefrontLink::with('usuario')
            ->activo()
            ->where('token', $bizToken)
            ->firstOrFail();

        $usuario = $link->usuario;

        // Servicios del usuario, solo activos
        $servicios = Servicio::where('usuario_id', $usuario->id)
            ->where('activo', true)     // <<--- aquí el cambio
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();

        return view('clientes.modoVistaTienda', [
            'usuario'   => $usuario,
            'servicios' => $servicios,
        ]);
    }


    public function perfil(string $bizToken, string $clientToken)
    {
        $link = StorefrontLink::with('usuario')->activo()
            ->where('token', $bizToken)->firstOrFail();

        $usuario = $link->usuario;

        $cliente = \DB::table('clientes')
            ->where('usuario_id', $usuario->id)
            ->where('public_token', $clientToken)
            ->where('activo', 1)
            ->firstOrFail();

        // Si quieres, arma $logoUrl exactamente como en show()
        $file    = $usuario->business_logo_filename ?? ($usuario->business_logo_path ? basename($usuario->business_logo_path) : null);
        $logoUrl = $file ? asset('storage/business-logos/' . $file) : null;

        return view('clientes.perfil', [
            'usuario'       => $usuario,
            'cliente'       => $cliente,
            'clienteNombre' => $cliente->nombre,
            'logoUrl'       => $logoUrl,
            'bizToken'      => $bizToken,
            'clientToken'   => $clientToken,
        ]);
    }

    public function logout(Request $request, string $bizToken, string $clientToken)
    {
        // No llevamos estado de sesión del cliente; “cerrar sesión”
        // es simplemente volver a la tienda sin el token del cliente.
        return redirect()->route('clientes.login', ['token' => $bizToken]);
    }
}
