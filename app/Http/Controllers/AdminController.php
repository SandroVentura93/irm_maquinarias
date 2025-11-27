<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bitacora;

class AdminController extends Controller
{
    public function bitacora()
    {
        $bitacora = Bitacora::all();
        return view('admin.bitacora', compact('bitacora'));
    }

    public function indexBitacoras()
    {
        return $this->bitacora();
    }
}