<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Moneda;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();

        $comprasQuery = Compra::with(['proveedor', 'moneda']);
        if ($request->filled('id_proveedor')) {
            $comprasQuery->where('id_proveedor', $request->id_proveedor);
        }
        // Importante: clonar antes de ordenar/paginar para no contaminar el query base usado en agregaciones
        $compras = (clone $comprasQuery)
            ->orderByDesc('fecha')
            ->paginate(15)
            ->appends($request->query());

        // Obtener ids de moneda PEN y USD
        $moneda_pen = Moneda::where('codigo_iso', 'PEN')->first();
        $moneda_usd = Moneda::where('codigo_iso', 'USD')->first();
        $id_pen = $moneda_pen->id_moneda ?? 1;
        $id_usd = $moneda_usd->id_moneda ?? 2;

        // Conteo por moneda
        $compras_count_pen = Compra::where('id_moneda', $id_pen)->count();
        $compras_count_usd = Compra::where('id_moneda', $id_usd)->count();

        // Totales por moneda (aplican al conjunto filtrado si hay filtro)
        $totales_por_moneda = (clone $comprasQuery)
            ->select('id_moneda', DB::raw('SUM(total) as monto'))
            ->groupBy('id_moneda')
            ->pluck('monto', 'id_moneda');

        $monto_total_pen = isset($totales_por_moneda[$id_pen]) ? (float) $totales_por_moneda[$id_pen] : 0;
        $monto_total_usd = isset($totales_por_moneda[$id_usd]) ? (float) $totales_por_moneda[$id_usd] : 0;

        // Compras del mes (conteo correcto global, no solo de la página actual)
        $compras_mes_count = (clone $comprasQuery)
            ->where('fecha', '>=', now()->startOfMonth())
            ->count();

        return view('compras.index', compact(
            'compras',
            'proveedores',
            'compras_count_pen',
            'compras_count_usd',
            'monto_total_pen',
            'monto_total_usd',
            'compras_mes_count'
        ));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        $monedas = Moneda::all();
        // Asegurar que el buscador tenga los campos necesarios e incluir nombre del proveedor
        $productos = Producto::leftJoin('proveedores', 'proveedores.id_proveedor', '=', 'productos.id_proveedor')
            ->select(
                'productos.id_producto',
                'productos.codigo',
                'productos.descripcion',
                'productos.id_proveedor',
                DB::raw('COALESCE(proveedores.razon_social, "") as proveedor_nombre'),
                DB::raw('COALESCE(proveedores.numero_documento, "") as proveedor_ruc')
            )
            ->orderBy('productos.descripcion')
            ->get();
        return view('compras.create', compact('proveedores', 'monedas', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_proveedor' => 'nullable|exists:proveedores,id_proveedor',
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

        // Derivar proveedor desde los productos si no se envió (independiente del índice del array)
        $idProveedor = $data['id_proveedor'] ?? null;
        if (!$idProveedor && !empty($data['detalles']) && is_array($data['detalles'])) {
            $primerDetalle = reset($data['detalles']);
            $primerProductoId = is_array($primerDetalle) ? ($primerDetalle['id_producto'] ?? null) : null;
            if ($primerProductoId) {
                $primerProducto = Producto::select('id_proveedor')->find($primerProductoId);
                $idProveedor = $primerProducto->id_proveedor ?? null;
            }
        }
        // Si no se pudo determinar proveedor para la cabecera, usar el proveedor del primer producto si existe
        if (!$idProveedor) {
            $primerDetalleTmp = reset($data['detalles']);
            $primerProductoIdTmp = is_array($primerDetalleTmp) ? ($primerDetalleTmp['id_producto'] ?? null) : null;
            if ($primerProductoIdTmp) {
                $primerProductoTmp = Producto::select('id_proveedor')->find($primerProductoIdTmp);
                $idProveedor = $primerProductoTmp->id_proveedor ?? null;
            }
        }
        // Nota: Se permite registrar productos de distintos proveedores en una sola compra.

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

        // Crear compra y detalles de forma atómica
        DB::transaction(function() use ($idProveedor, $data, $subtotal, $igv, $total) {
            $compra = new Compra();
            $compra->id_proveedor = $idProveedor;
            $compra->id_moneda = $data['id_moneda'];
            $compra->fecha = $data['fecha'];
            // Nota: algunas bases no tienen columna incluir_igv; no asignar si no existe
            $compra->subtotal = $subtotal;
            $compra->igv = $igv;
            $compra->total = $total;
            // Nota: No asignar tipo_cambio si la columna no existe en la tabla compras
            $compra->save();

            foreach ($data['detalles'] as $detalle) {
                $detalle['id_compra'] = $compra->id_compra;
                DetalleCompra::create($detalle);
            }
        });

        return redirect()->route('compras.index')->with('success', 'Compra registrada correctamente');
    }

    public function show($id)
    {
        $compra = Compra::with(['proveedor', 'moneda', 'detalles.producto'])->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    public function edit($id)
    {
        // Eager load proveedor y moneda de la compra, y el proveedor de cada producto en los detalles
        $compra = Compra::with(['proveedor', 'moneda', 'detalles.producto.proveedor'])->findOrFail($id);
        $proveedores = Proveedor::all();
        $monedas = Moneda::all();
        // Incluir datos de proveedor para buscador en la vista de edición
        $productos = Producto::leftJoin('proveedores', 'proveedores.id_proveedor', '=', 'productos.id_proveedor')
            ->select(
                'productos.id_producto',
                'productos.codigo',
                'productos.descripcion',
                'productos.id_proveedor',
                DB::raw('COALESCE(proveedores.razon_social, "") as proveedor_nombre'),
                DB::raw('COALESCE(proveedores.numero_documento, "") as proveedor_ruc')
            )
            ->orderBy('productos.descripcion')
            ->get();
        return view('compras.edit', compact('compra', 'proveedores', 'monedas', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_proveedor' => 'nullable|exists:proveedores,id_proveedor',
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

        // Derivar proveedor desde los productos si no se envió (independiente del índice del array)
        $idProveedorUpd = $data['id_proveedor'] ?? null;
        if (!$idProveedorUpd && !empty($data['detalles']) && is_array($data['detalles'])) {
            $primerDetalleUpd = reset($data['detalles']);
            $primerProductoId = is_array($primerDetalleUpd) ? ($primerDetalleUpd['id_producto'] ?? null) : null;
            if ($primerProductoId) {
                $primerProducto = Producto::select('id_proveedor')->find($primerProductoId);
                $idProveedorUpd = $primerProducto->id_proveedor ?? null;
            }
        }
        // Permitir edición con múltiples proveedores; si no hay proveedor cabecera, derivar del primer detalle
        if (!$idProveedorUpd) {
            $primerDetalleTmp = reset($data['detalles']);
            $primerProductoIdTmp = is_array($primerDetalleTmp) ? ($primerDetalleTmp['id_producto'] ?? null) : null;
            if ($primerProductoIdTmp) {
                $primerProductoTmp = Producto::select('id_proveedor')->find($primerProductoIdTmp);
                $idProveedorUpd = $primerProductoTmp->id_proveedor ?? null;
            }
        }

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
        // Actualizar compra y detalles de forma atómica
        DB::transaction(function() use ($compra, $idProveedorUpd, $data, $subtotal, $igv, $total) {
            $compra->id_proveedor = $idProveedorUpd;
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
        });
        // Redirigir al índice de compras después de actualizar
        return redirect()->route('compras.index')->with('success', 'Compra actualizada correctamente');
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
