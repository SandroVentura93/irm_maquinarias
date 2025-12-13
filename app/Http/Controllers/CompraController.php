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
    public function __construct()
    {
        $this->middleware('admin')->only(['destroy']);
    }
    public function index()
    {
        $compras = Compra::with(['proveedor', 'moneda'])->orderByDesc('fecha')->paginate(15);

        // Obtener ids de moneda PEN y USD
        $moneda_pen = Moneda::where('codigo_iso', 'PEN')->first();
        $moneda_usd = Moneda::where('codigo_iso', 'USD')->first();
        $id_pen = $moneda_pen->id_moneda ?? 1;
        $id_usd = $moneda_usd->id_moneda ?? 2;

        // Conteo por moneda
        $compras_count_pen = Compra::where('id_moneda', $id_pen)->count();
        $compras_count_usd = Compra::where('id_moneda', $id_usd)->count();

        // Totales por moneda (sumar total tal cual, aquí las compras no tienen estado XML)
        $totales_por_moneda = \DB::table('compras')
            ->select('id_moneda', \DB::raw('SUM(total) as monto'))
            ->groupBy('id_moneda')
            ->pluck('monto', 'id_moneda');

        $monto_total_pen = isset($totales_por_moneda[$id_pen]) ? (float) $totales_por_moneda[$id_pen] : 0;
        $monto_total_usd = isset($totales_por_moneda[$id_usd]) ? (float) $totales_por_moneda[$id_usd] : 0;

        return view('compras.index', compact('compras', 'compras_count_pen', 'compras_count_usd', 'monto_total_pen', 'monto_total_usd'));
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
            'incluir_igv' => 'nullable|boolean',
            'tipo_cambio_manual' => 'required|numeric|min:0.0001',
            'moneda_codigo' => 'nullable|string',
            'detalles' => 'required|array',
            'detalles.*.id_producto' => 'required|exists:productos,id_producto',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric',
        ]);

        // Verificar coherencia entre id_moneda y moneda_codigo (si se envía)
        if (!empty($data['moneda_codigo'])) {
            $moneda = Moneda::find($data['id_moneda']);
            if ($moneda && strtoupper($data['moneda_codigo']) !== strtoupper($moneda->codigo_iso)) {
                return back()->withErrors(['id_moneda' => 'La moneda seleccionada no coincide con el código enviado.'])->withInput();
            }
        }
        // Determinar si se debe calcular IGV
        $incluir_igv = $data['incluir_igv'] ?? false;

        // Calcular totales de la compra en la moneda seleccionada
        $subtotal = 0;
        $igv = 0;
        $total = 0;
        foreach ($data['detalles'] as &$detalle) {
            $detalle['subtotal'] = $detalle['cantidad'] * $detalle['precio_unitario'];
            $detalle['igv'] = $incluir_igv ? $detalle['subtotal'] * 0.18 : 0;
            $detalle['total'] = $detalle['subtotal'] + $detalle['igv'];
            $subtotal += $detalle['subtotal'];
            $igv += $detalle['igv'];
            $total += $detalle['total'];
        }

        // Crear compra con totales y tipo de cambio manual
        $compra = new Compra();
        $compra->id_proveedor = $data['id_proveedor'];
        $compra->id_moneda = $data['id_moneda'];
        $compra->fecha = $data['fecha'];
        // Nota: algunas bases no tienen columna incluir_igv; no asignar si no existe
        $compra->subtotal = $subtotal;
        $compra->igv = $igv;
        $compra->total = $total;
        // Nota: No asignar tipo_cambio si la columna no existe en la tabla compras
        $compra->save();

        // Guardar detalles
        foreach ($data['detalles'] as $detalle) {
            $detalle['id_compra'] = $compra->id_compra;
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
            'incluir_igv' => 'nullable|boolean',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
            'detalles' => 'required|array',
            'detalles.*.id_producto' => 'required|exists:productos,id_producto',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric',
        ]);

        $compra = Compra::findOrFail($id);

        // Determinar si se debe calcular IGV
        $incluir_igv = $data['incluir_igv'] ?? false;

        // Calcular totales
        $subtotal = 0;
        $igv = 0;
        $total = 0;
        foreach ($data['detalles'] as &$detalle) {
            $detalle['subtotal'] = $detalle['cantidad'] * $detalle['precio_unitario'];
            $detalle['igv'] = $incluir_igv ? $detalle['subtotal'] * 0.18 : 0;
            $detalle['total'] = $detalle['subtotal'] + $detalle['igv'];
            $subtotal += $detalle['subtotal'];
            $igv += $detalle['igv'];
            $total += $detalle['total'];
        }
        // Asignar campos manualmente evitando columnas inexistentes
        $compra->id_proveedor = $data['id_proveedor'];
        $compra->id_moneda = $data['id_moneda'];
        $compra->fecha = $data['fecha'];
        $compra->subtotal = $subtotal;
        $compra->igv = $igv;
        $compra->total = $total;
        $compra->save();
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

    public function productosPorProveedor($id_proveedor)
    {
        $productos = Producto::where('id_proveedor', $id_proveedor)
            ->select('id_producto', 'codigo', 'descripcion')
            ->orderBy('descripcion')
            ->get();
        
        return response()->json($productos);
    }
}
