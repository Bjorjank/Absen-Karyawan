<?php
// app/Http/Controllers/IzinController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function urusan()
    {
        return view('izin.urusan');
    }

    public function sakit()
    {
        return view('izin.sakit'); // Ubah ini jika perlu view yang berbeda
    }

    public function cuti()
    {
        return view('izin.cuti');
    }

    
}
