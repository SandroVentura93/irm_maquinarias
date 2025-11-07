<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function clientes(Request $request)
    {
        $dniRuc = $request->get('dni_ruc', '');
        
        if (strlen($dniRuc) < 3) {
            return response()->json([]);
        }
        
        $clientes = Cliente::where('activo', true)
            ->where(function($query) use ($dniRuc) {
                $query->where('numero_documento', 'LIKE', '%' . $dniRuc . '%')
                      ->orWhere('nombre', 'LIKE', '%' . $dniRuc . '%')
                      ->orWhere('razon_social', 'LIKE', '%' . $dniRuc . '%');
            })
            ->limit(10)
            ->get(['id_cliente', 'nombre', 'razon_social', 'numero_documento']);
        
        $resultado = $clientes->map(function($cliente) {
            return [
                'id' => $cliente->id_cliente,
                'nombre' => $cliente->nombre ?: $cliente->razon_social,
                'numero_documento' => $cliente->numero_documento
            ];
        });
        
        return response()->json($resultado);
    }
    
    public function productos(Request $request)
    {
        $nombre = $request->get('nombre', '');
        
        if (strlen($nombre) < 3) {
            return response()->json([]);
        }
        
        $productos = Producto::where('activo', true)
            ->where('stock_actual', '>', 0)
            ->where(function($query) use ($nombre) {
                $query->where('descripcion', 'LIKE', '%' . $nombre . '%')
                      ->orWhere('codigo', 'LIKE', '%' . $nombre . '%');
            })
            ->limit(10)
            ->get(['id_producto', 'descripcion', 'precio_venta', 'stock_actual']);
        
        return response()->json($productos);
    }
    
    public function producto($id)
    {
        $producto = Producto::where('activo', true)
            ->find($id, ['id_producto', 'descripcion', 'precio_venta', 'stock_actual']);
        
        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        
        return response()->json($producto);
    }
}
