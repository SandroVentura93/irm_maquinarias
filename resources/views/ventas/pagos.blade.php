@extends('layouts.dashboard')

@section('content')
<div class="container mt-4">
    <h1 class="text-primary">Registrar Pago</h1>

    <!-- Información del saldo -->
    <div class="alert alert-info mb-3">
        <div class="row">
            <div class="col-md-6" id="colSaldoSoles">
                <h6 class="mb-0">
                    <i class="fas fa-flag-pe me-2"></i>
                    <strong>Soles:</strong> <span id="saldoPendienteSoles" class="fw-bold fs-5">S/ 0.00</span>
                </h6>
            </div>
            <div class="col-md-6" id="colSaldoDolares">
                <h6 class="mb-0">
                    <i class="fas fa-flag-usa me-2"></i>
                    <strong>Dólares:</strong> <span id="saldoPendienteDolares" class="fw-bold fs-5">$ 0.00</span>
                </h6>
            </div>
        </div>

        <div class="mt-2 text-muted small d-flex align-items-center justify-content-between">
            <span>
                <i class="fas fa-exchange-alt me-1"></i>
                Tipo de cambio: S/ <span id="tipoCambioDisplay" class="fw-bold">...</span> por USD
            </span>
            <span class="badge bg-info" id="fuenteTipoCambio">
                <i class="fas fa-sync-alt"></i> Cargando...
            </span>
        </div>

        <!-- Tipo de cambio -->
        <div class="input-group input-group-sm mt-3" id="grupoTipoCambio">
            <span class="input-group-text">TC USD</span>
            <input type="number" step="0.0001" min="0" class="form-control" id="tipoCambioManual" placeholder="3.8000">
            <button type="button" class="btn btn-outline-primary btn-sm" id="aplicarTipoCambio">
                <i class="fas fa-check"></i> Aplicar
            </button>
        </div>
    </div>

    <form id="formPago" method="POST" action="#">
        <input type="hidden" name="id_venta" id="pago_id_venta">
        <input type="hidden" id="pago_moneda" name="moneda">
        <input type="hidden" id="pago_tipo_cambio" name="tipo_cambio">

        <div class="mb-3">
            <label for="pago_monto" class="form-label">Monto a Pagar</label>
            <div class="input-group">
                <span class="input-group-text" id="pagoSimbolo">S/</span>
                <input type="number" step="0.01" class="form-control" id="pago_monto" name="monto" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="pago_metodo" class="form-label">Método de Pago</label>
            <select class="form-select" id="pago_metodo" name="metodo" required>
                <option value="">Seleccione</option>
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Yape">Yape</option>
                <option value="Plin">Plin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="pago_numero_operacion" class="form-label">Número de Operación</label>
            <input type="text" class="form-control" id="pago_numero_operacion"
                name="numero_operacion" placeholder="Ej. N° de operación bancaria o de POS">
            <div class="form-text">Opcional: útil para transferencias, tarjetas, Yape/Plin.</div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Registrar Pago</button>
        </div>
    </form>

    <!-- Historial -->
    <div class="mt-4">
        <h6>Historial de Pagos</h6>
        <ul id="historialPagos" class="list-group"></ul>
    </div>
</div>
@endsection

@section('scripts')
@once
<script>
/* ============================================================
   SCRIPT ÚNICO — SIN DUPLICACIONES
   ============================================================ */
(function () {

    // Para garantizar que no se cargue 2 veces
    if (window.__pagosInit) return;
    window.__pagosInit = true;

    // Namespace único para evitar conflictos
    const PAGO = {

        cargarHistorial() {
            const lista = document.getElementById('historialPagos');
            if (!lista) return;

            const pagos = [
                { id: 1, monto: 'S/ 100.00', metodo: 'Efectivo', fecha: '2025-12-08' },
                { id: 2, monto: '$ 50.00', metodo: 'Transferencia', fecha: '2025-12-07' }
            ];

            lista.innerHTML = "";
            pagos.forEach(pago => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = `${pago.fecha} - ${pago.metodo}: ${pago.monto}`;
                lista.appendChild(li);
            });
        },

        configurarTipoCambio() {
            const input = document.getElementById('tipoCambioManual');
            const btn = document.getElementById('aplicarTipoCambio');
            const display = document.getElementById('tipoCambioDisplay');

            if (!input || !btn || !display) return;

            btn.addEventListener('click', () => {
                const valor = parseFloat(input.value);
                if (valor > 0) {
                    display.textContent = valor.toFixed(4);
                } else {
                    alert("Ingrese un tipo de cambio válido.");
                }
            });
        }
    };

    // DOM listo — se ejecuta UNA sola vez
    document.addEventListener("DOMContentLoaded", () => {
        PAGO.cargarHistorial();
        PAGO.configurarTipoCambio();
    });

})();

console.log('CARGÓ pagos.blade.php — render completo');
</script>
@endonce
@endsection
