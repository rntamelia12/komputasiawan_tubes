<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        $pageTitle = 'My Profile';
        $user = Auth::user(); // Ambil data pengguna yang login

        return view('profile', compact('pageTitle', 'user'));
    }
}
