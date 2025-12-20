@extends('layouts.dashboard')

@section('content')
<div class="container mt-4">
    <h1 class="text-primary">Registrar Pago</h1>

    <!-- Información del saldo (unificado a la moneda de la venta) -->
    @php
        $codigoIso = strtoupper(optional($venta->moneda)->codigo_iso ?? 'PEN');
        $simbolo = $codigoIso === 'USD' ? '$' : 'S/';
        $icono = $codigoIso === 'USD' ? 'fas fa-dollar-sign' : 'fas fa-money-bill-wave';
        $saldoMostrado = (float)($venta->saldo_calculado ?? $venta->saldo ?? 0);
    @endphp
    <div class="alert alert-info mb-3">
        <h6 class="mb-0">
            <strong>Saldo pendiente:</strong>
            <span class="fw-bold fs-5" id="saldoPendienteUnified">
                <i class="{{ $icono }} me-1"></i>{{ $simbolo }} {{ number_format($saldoMostrado, 2) }}
            </span>
            <span class="badge bg-light text-dark ms-2" id="badgeMoneda">{{ $codigoIso }}</span>
        </h6>
    </div>

    <form id="formPago" method="POST" action="{{ url('/ventas/' . ($venta->id_venta ?? '') . '/pago') }}">
        @csrf

        <input type="hidden" name="id_venta" id="pago_id_venta" value="{{ $venta->id_venta ?? '' }}">
        <input type="hidden" id="pago_moneda" name="pago_moneda" value="{{ $codigoIso }}">
        <input type="hidden" id="pago_tipo_cambio" name="tipo_cambio" value="{{ $venta->tipo_cambio ?? 1 }}">

        <div class="mb-3">
            <label for="pago_monto" class="form-label">Monto a Pagar</label>
            <div class="input-group">
                <span class="input-group-text" id="pagoSimbolo">{{ $simbolo }}</span>
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

    <!-- Historial (opcional) -->
    <div class="mt-4 d-none" id="historialPagosWrapper">
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
    const PAGO = {};

    // DOM listo — se ejecuta UNA sola vez
    document.addEventListener("DOMContentLoaded", () => {
        // Inicialización mínima sin cargar componentes extra
    });

})();

console.log('CARGÓ pagos.blade.php — render completo');
</script>
@endonce
@endsection
