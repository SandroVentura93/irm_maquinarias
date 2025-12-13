<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use Illuminate\Http\Request;

class DetalleCompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['destroy']);
    }
    public function index()
    {
        $detalles = DetalleCompra::with(['compra', 'producto'])->paginate(20);
        return view('detalle_compras.index', compact('detalles'));
    }

    public function show($id)
    {
        $detalle = DetalleCompra::with(['compra', 'producto'])->findOrFail($id);
        return view('detalle_compras.show', compact('detalle'));
    }

    public function destroy($id)
    {
        $detalle = DetalleCompra::findOrFail($id);
        $detalle->delete();
        return redirect()->route('detalle_compras.index')->with('success', 'Detalle eliminado correctamente');
    }
}
