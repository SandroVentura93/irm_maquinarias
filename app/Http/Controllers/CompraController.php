<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Moneda;
use App\Models\Producto;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with(['proveedor', 'moneda'])->orderByDesc('fecha')->paginate(15);
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        $monedas = Moneda::all();
        $productos = Producto::all();
        return view('compras.create', compact('proveedores', 'monedas', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'id_moneda' => 'required|exists:monedas,id_moneda',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
            'detalles' => 'required|array',
            'detalles.*.id_producto' => 'required|exists:productos,id_producto',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric',
        ]);

        $compra = Compra::create($data);
        foreach ($data['detalles'] as $detalle) {
            $detalle['id_compra'] = $compra->id_compra;
            $detalle['subtotal'] = $detalle['cantidad'] * $detalle['precio_unitario'];
            $detalle['igv'] = $detalle['subtotal'] * 0.18;
            $detalle['total'] = $detalle['subtotal'] + $detalle['igv'];
            DetalleCompra::create($detalle);
        }
        return redirect()->route('compras.index')->with('success', 'Compra registrada correctamente');
    }

    public function show($id)
    {
        $compra = Compra::with(['proveedor', 'moneda', 'detalles.producto'])->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    public function edit($id)
    {
        $compra = Compra::with(['detalles'])->findOrFail($id);
        $proveedores = Proveedor::all();
        $monedas = Moneda::all();
        $productos = Producto::all();
        return view('compras.edit', compact('compra', 'proveedores', 'monedas', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'id_moneda' => 'required|exists:monedas,id_moneda',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
            'detalles' => 'required|array',
            'detalles.*.id_producto' => 'required|exists:productos,id_producto',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric',
        ]);
        $compra = Compra::findOrFail($id);
        // Calcular totales
        $subtotal = 0;
        $igv = 0;
        $total = 0;
        foreach ($data['detalles'] as &$detalle) {
            $detalle['subtotal'] = $detalle['cantidad'] * $detalle['precio_unitario'];
            $detalle['igv'] = $detalle['subtotal'] * 0.18;
            $detalle['total'] = $detalle['subtotal'] + $detalle['igv'];
            $subtotal += $detalle['subtotal'];
            $igv += $detalle['igv'];
            $total += $detalle['total'];
        }
        $data['subtotal'] = $subtotal;
        $data['igv'] = $igv;
        $data['total'] = $total;
        $compra->update($data);
        $compra->detalles()->delete();
        foreach ($data['detalles'] as $detalle) {
            $detalle['id_compra'] = $compra->id_compra;
            DetalleCompra::create($detalle);
        }
        return redirect()->route('compras.show', $compra->id_compra)->with('success', 'Compra actualizada correctamente');
    }

    public function destroy($id)
    {
        $compra = Compra::findOrFail($id);
        $compra->detalles()->delete();
        $compra->delete();
        return redirect()->route('compras.index')->with('success', 'Compra eliminada correctamente');
    }
}
