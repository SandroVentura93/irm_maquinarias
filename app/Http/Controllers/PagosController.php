<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;

class PagosController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['destroy']);
    }
    /**
     * Muestra la lista de pagos.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return view('ventas.partials.pagos'); // Devuelve un fragmento si es AJAX
        }

        $venta = null; // Aquí puedes obtener la venta desde la base de datos
        return view('ventas.pagos', ['venta' => $venta]);
    }

    /**
     * Muestra el formulario para crear un nuevo pago.
     */
    public function create()
    {
        return view('ventas.pagos.create');
    }

    /**
     * Almacena un nuevo pago en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id_venta',
            'monto' => 'required|numeric|min:0.01',
            'pago_moneda' => 'required|string',
            'tipo_pago' => 'required|string|max:50',
            'numero_operacion' => 'nullable|string|max:255',
        ]);

        $venta = Venta::findOrFail($request->venta_id);

        // Depuración: Verificar datos recibidos
        \Log::info('Datos recibidos para registrar pago:', $request->all());

        // Depuración: Verificar si la venta existe
        if (!$venta) {
            \Log::error('Venta no encontrada con ID: ' . $request->venta_id);
            return back()->withErrors(['error' => 'Venta no encontrada.']);
        }

        // Registrar el pago
        $venta->pagos()->create([
            'monto' => $request->monto,
            'moneda' => $request->pago_moneda,
            'metodo' => $request->tipo_pago,
            'numero_operacion' => $request->numero_operacion,
            'fecha' => now(),
        ]);

        // Depuración: Confirmar registro del pago
        \Log::info('Pago registrado correctamente para la venta ID: ' . $venta->id_venta);

        // Actualizar el saldo de la venta
        $totalPagado = $venta->pagos()->sum('monto');
        $venta->saldo = max($venta->total - $totalPagado, 0);
        $venta->save();

        return redirect()->route('ventas.index')->with('success', 'Pago registrado exitosamente.');
    }

    /**
     * Muestra los detalles de un pago específico con el saldo calculado.
     */
    public function show($id)
    {
        $venta = Venta::with(['pagos', 'moneda'])->where('id_venta', $id)->first();

        if (!$venta) {
            return redirect()->route('ventas.index')->with('error', 'Venta no encontrada.');
        }

        // Calcular el saldo
        $totalPagado = $venta->pagos->sum('monto');
        $saldo = $venta->total - $totalPagado;

        // Determinar la moneda basada en la relación de la venta
        $monedaNombre = $venta->moneda ? ($venta->moneda->nombre === 'Dólar Estadounidense' ? 'Dolares' : $venta->moneda->nombre) : 'Soles';
        $monedaSimbolo = $venta->moneda ? $venta->moneda->simbolo : 'S/';

        $moneda = [
            'nombre' => $monedaNombre,
            'simbolo' => $monedaSimbolo,
        ];

        return view('ventas.pagos.show', [
            'venta' => $venta,
            'saldo' => $saldo,
            'id' => $id,
            'moneda' => $monedaNombre,
            'simbolo' => $monedaSimbolo
        ]);
    }

    /**
     * Muestra el formulario para editar un pago existente.
     */
    public function edit($id)
    {
        // Aquí puedes agregar lógica para obtener un pago específico
        return view('ventas.pagos.edit', compact('id'));
    }

    /**
     * Actualiza un pago existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        // Validar y actualizar el pago
        $request->validate([
            'monto' => 'required|numeric',
            'moneda' => 'required|string',
            'fecha' => 'required|date',
        ]);

        // Aquí puedes agregar lógica para actualizar el pago

        return redirect()->route('ventas.pagos.index')->with('success', 'Pago actualizado exitosamente.');
    }

    /**
     * Elimina un pago específico de la base de datos.
     */
    public function destroy($id)
    {
        // Aquí puedes agregar lógica para eliminar el pago

        return redirect()->route('ventas.pagos.index')->with('success', 'Pago eliminado exitosamente.');
    }
}