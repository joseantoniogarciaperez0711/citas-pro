<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileBusinessLogoController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'business_logo' => ['required', 'image', 'max:2048'], // 2MB
        ]);

        $user = $request->user();

        if ($user->business_logo_path) {
            Storage::disk('public')->delete($user->business_logo_path);
        }

        $path = $request->file('business_logo')->store('business-logos', 'public');
        $user->forceFill(['business_logo_path' => $path])->save();

        return back()->with('status', 'business-logo-updated');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->business_logo_path) {
            Storage::disk('public')->delete($user->business_logo_path);
            $user->forceFill(['business_logo_path' => null])->save();
        }

        return back()->with('status', 'business-logo-deleted');
    }
}
