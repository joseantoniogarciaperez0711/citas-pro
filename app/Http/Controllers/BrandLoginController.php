<?php

namespace App\Http\Controllers;

use App\Models\StorefrontLink;
use Illuminate\Http\Request;

class BrandLoginController extends Controller
{
    public function login(string $token)
    {
        $link = StorefrontLink::with('usuario')->activo()
            ->where('token', $token)->firstOrFail();

        $u = $link->usuario;

        // ✅ Pasa el valor crudo de la BD, sin normalizar
        $logoPath = $u->business_logo_path;      // p.ej. "business-logos/archivo.png"
        $logoUrl  = $u->business_logo_url;       // opcional: si guardas una URL completa

        return view('clientes.login', [
            'usuario'  => $u,
            'logoPath' => $logoPath,
            'logoUrl'  => $logoUrl,
            'token'    => $token,
        ]);
    }

    public function fromToken(string $token)
    {
        $link = StorefrontLink::with('usuario')->activo()
            ->where('token', $token)->firstOrFail();

        $u = $link->usuario;

        // Guarda crudo en sesión por si lo necesitas luego
        session([
            'brand' => [
                'business_name' => $u->business_name,
                'logo_path'     => $u->business_logo_path, // ← crudo
                'logo_url'      => $u->business_logo_url,  // ← si existe
            ],
        ]);

        // ✅ redirige a la ruta correcta con token
        return redirect()->route('clientes.login', ['token' => $token]);
    }

    public function clear(string $token = null)
    {
        session()->forget('brand');

        return $token
            ? redirect()->route('clientes.login', ['token' => $token])
            : back();
    }
}
