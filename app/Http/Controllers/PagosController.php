<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;

class PagosController extends Controller
{
    /**
     * Muestra la lista de pagos.
     */
    public function index()
    {
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
        // Validar y almacenar el pago
        $request->validate([
            'monto' => 'required|numeric',
            'moneda' => 'required|string',
            'fecha' => 'required|date',
        ]);

        // Aquí puedes agregar lógica para guardar el pago

        return redirect()->route('ventas.pagos.index')->with('success', 'Pago registrado exitosamente.');
    }

    /**
     * Muestra los detalles de un pago específico.
     */
    public function show($id)
    {
        $venta = Venta::find($id);

        if (!$venta) {
            return redirect()->route('ventas.index')->with('error', 'Venta no encontrada.');
        }

        return view('ventas.pagos', ['venta' => $venta, 'id' => $id]);
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