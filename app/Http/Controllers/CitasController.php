<?php

namespace App\Http\Controllers;

class CitasController extends Controller
{
    public function dashboard()    { return view('citas.dashboard'); }
    public function appointments() { return view('citas.appointments'); }
    public function clients()      { return view('citas.clients'); }
    public function services()     { return view('citas.services'); }
    public function settings()     { return view('citas.settings'); }
}
