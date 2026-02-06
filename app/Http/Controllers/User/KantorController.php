<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kantor;

class KantorController extends Controller
{
    public function index()
    {
        $kantors = Kantor::all(); // ambil semua kantor
        return view('user.kantor', compact('kantors'));
    }
}
