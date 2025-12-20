@extends('layouts.dashboard')

@section('content')


<style>
    .pago-full-bg {
        min-height: 100vh;
        background: linear-gradient(120deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 0;
        margin: 0;
    }
    .pago-card-pro {
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(80,80,120,0.13);
        overflow: hidden;
        background: linear-gradient(120deg, #fff 60%, #e0e7ff 100%);
        border: none;
    }
    .pago-card-header-pro {
        background: linear-gradient(90deg, #6366f1 0%, #4f46e5 100%);
        color: #fff;
        padding: 2.2rem 2rem 1.2rem 2rem;
        border-bottom: none;
    }
    .pago-card-header-pro h3 {
        font-weight: 700;
        font-size: 2.1rem;
        margin-bottom: 0;
        letter-spacing: 1px;
    }
    .pago-card-body-pro {
        padding: 2.5rem 2.5rem 2rem 2.5rem;
    }
    .pago-form-label {
        font-weight: 600;
        color: #4f46e5;
        font-size: 1.08rem;
    }
    .pago-form-control {
        border-radius: 12px;
        font-size: 1.15rem;
        padding: 0.9rem 1.1rem;
        border: 2px solid #e0e7ff;
        background: #f8fafc;
        transition: border-color 0.2s;
    }
    .pago-form-control:focus {
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 2px #6366f133;
    }
    .pago-btn-pro {
        border-radius: 10px;
        font-size: 1.15rem;
        font-weight: 600;
        padding: 0.85rem 2.5rem;
        box-shadow: 0 4px 16px rgba(80,80,120,0.10);
    }
    .pago-saldo-box {
        background: linear-gradient(90deg, #e0e7ff 0%, #fff 100%);
        border-radius: 14px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.07);
    }
    .pago-saldo-box .saldo-label {
        color: #6366f1;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .pago-saldo-box .saldo-valor {
        font-size: 2.1rem;
        font-weight: 800;
        color: #10b981;
        letter-spacing: 1px;
    }
    .pago-historial-card {
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(99,102,241,0.08);
        margin-top: 2.5rem;
        border: none;
    }
    .pago-historial-header {
        background: #f8fafc;
        border-bottom: 1px solid #e0e7ff;
        border-radius: 18px 18px 0 0;
        padding: 1.2rem 2rem 1rem 2rem;
    }
    .pago-historial-header h5 {
        color: #4f46e5;
        font-weight: 700;
        margin-bottom: 0;
    }
    .pago-historial-list .list-group-item {
        font-size: 1.08rem;
        padding: 1.1rem 2rem;
        border: none;
        border-bottom: 1px solid #f1f5f9;
        background: transparent;
    }
    .pago-historial-list .list-group-item:last-child {
        border-bottom: none;
    }
    .pago-historial-list .badge {
        font-size: 0.98rem;
        font-weight: 600;
        background: #e0e7ff;
        color: #4f46e5;
    }
</style>

<div class="pago-full-bg">
    <div class="container-fluid px-0">
        <div class="pago-card-pro my-5 mx-auto" style="max-width: 1100px;">
            <div class="pago-card-header-pro">
                <h3><i class="fas fa-cash-register me-2"></i>Registrar Pago para la Venta <span class="badge bg-light text-primary">#{{ $id }}</span></h3>
            </div>
            <div class="pago-card-body-pro">
                <div class="pago-saldo-box mb-4">
                    <i class="fas fa-wallet fa-2x"></i>
                    <span class="saldo-label">Saldo Pendiente:</span>
                    <span class="saldo-valor">{{ $simbolo }} {{ number_format($saldo, 2) }}</span>
                </div>
                <form id="formPago" method="POST" action="{{ route('ventas.pago', ['venta' => $id]) }}">
                    @csrf
                    <input type="hidden" name="venta_id" value="{{ $id }}">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="pago_monto" class="pago-form-label">Monto a Pagar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary fw-bold">{{ $simbolo }}</span>
                                <input type="number" step="0.01" min="0" class="form-control pago-form-control" id="pago_monto" name="monto" required value="{{ number_format($saldo, 2, '.', '') }}" placeholder="{{ number_format($saldo, 2, '.', '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="pago_moneda" class="pago-form-label">Moneda del Pago</label>
                            <select class="form-select pago-form-control" id="pago_moneda" name="pago_moneda_label" required disabled>
                                <option value="Soles" {{ $moneda === 'Soles' ? 'selected' : '' }}>Soles</option>
                                <option value="Dolares" {{ $moneda === 'Dolares' ? 'selected' : '' }}>Dólares</option>
                            </select>
                            <!-- Hidden field to ensure currency code is submitted even when select is disabled -->
                            <input type="hidden" name="pago_moneda" value="{{ $codigoIso }}">
                        </div>
                        <div class="col-md-6">
                            <label for="metodo" class="pago-form-label">Tipo de Pago</label>
                            <select class="form-select pago-form-control" id="metodo" name="metodo" required>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Yape">Yape</option>
                                <option value="Plin">Plin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="numero_operacion" class="pago-form-label">Número de Operación</label>
                            <input type="text" class="form-control pago-form-control" id="numero_operacion" name="numero_operacion" required placeholder="">
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success pago-btn-pro"><i class="fas fa-check-circle me-2"></i>Registrar Pago</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="pago-historial-card mx-auto mb-5" style="max-width: 1100px;">
            <div class="pago-historial-header d-flex align-items-center">
                <i class="fas fa-history me-2 text-primary"></i>
                <h5 class="mb-0">Historial de Pagos</h5>
            </div>
            <div class="pago-historial-list">
                <ul class="list-group list-group-flush">
                    @forelse ($venta->pagos as $pago)
                        @php
                            $rawMon = strtoupper(trim((string)($pago->moneda ?? 'PEN')));
                            $esUSD = ($rawMon === '$') || str_contains($rawMon, 'USD') || str_contains($rawMon, 'DOLAR') || str_contains($rawMon, 'DÓLAR');
                            $labelMon = $esUSD ? 'Dólares' : 'Soles';
                            $simboloMon = $esUSD ? '$' : 'S/';
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                <span class="fw-semibold">{{ $pago->fecha }}</span>
                                <span class="badge ms-2">{{ $labelMon }}</span>
                            </span>
                            <span class="fw-bold text-success">{{ $simboloMon }} {{ number_format($pago->monto, 2) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">No hay pagos registrados.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Inyección de la variable global para el saldo pendiente
    window.SALDO_PENDIENTE = {{ $saldo }};
</script>
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

        const saldo = {{ $saldo ?? 0 }}; // Aseguramos que el saldo sea un número válido
        const montoInput = document.getElementById("pago_monto");

        // Llenar automáticamente el monto a pagar con el saldo pendiente
        if (montoInput && !isNaN(saldo)) {
            montoInput.value = parseFloat(saldo).toFixed(2);
            console.log("Monto a pagar actualizado:", montoInput.value); // Depuración
        } else {
            console.error("Saldo no válido o elemento no encontrado.", { saldo });
        }

        const tipoPagoSelect = document.getElementById("tipo_pago");
        const numeroOperacionInput = document.getElementById("numero_operacion");

        // Función para actualizar el estado del número de operación
        const actualizarEstadoNumeroOperacion = () => {
            if (tipoPagoSelect.value === "Efectivo") {
                numeroOperacionInput.disabled = true;
                numeroOperacionInput.value = "";
                numeroOperacionInput.placeholder = "No aplica para efectivo"; // Refuerzo visual
            } else {
                numeroOperacionInput.disabled = false;
                numeroOperacionInput.placeholder = "Ingrese el número de operación"; // Refuerzo visual
            }
        };

        // Enlazar el evento change al select de tipo de pago
        tipoPagoSelect.addEventListener("change", actualizarEstadoNumeroOperacion);

        // Inicializar el estado del número de operación al cargar la página
        actualizarEstadoNumeroOperacion();

        // Verificación adicional para garantizar que el atributo disabled se aplique correctamente
        const verificarEstadoDisabled = () => {
            if (tipoPagoSelect.value === "Efectivo" && !numeroOperacionInput.disabled) {
                numeroOperacionInput.disabled = true;
            } else if (tipoPagoSelect.value !== "Efectivo" && numeroOperacionInput.disabled) {
                numeroOperacionInput.disabled = false;
            }
        };

        // Refuerzo adicional: verificar el estado cada vez que se interactúe con el formulario
        tipoPagoSelect.addEventListener("blur", verificarEstadoDisabled);

        // Refuerzo adicional: verificar el estado al hacer clic en el botón de registrar
        const registrarPagoBtn = document.querySelector("button[type='submit']");
        registrarPagoBtn.addEventListener("click", verificarEstadoDisabled);
    });
})();

document.addEventListener("DOMContentLoaded", () => {
    const tipoCambioInput = document.getElementById("tipo_cambio");
    const monedaSelect = document.getElementById("pago_moneda");
    const tipoPagoSelect = document.getElementById("tipo_pago");
    const numeroOperacionInput = document.getElementById("numero_operacion");

    monedaSelect.addEventListener("change", () => {
        if (monedaSelect.value === "Dolares") {
            tipoCambioInput.disabled = false;
        } else {
            tipoCambioInput.disabled = true;
            tipoCambioInput.value = "";
        }
    });

    // Inicializar estado del tipo de cambio
    if (monedaSelect.value === "Dolares") {
        tipoCambioInput.disabled = false;
    } else {
        tipoCambioInput.disabled = true;
    }

    const simboloSpan = document.querySelector(".input-group-text");

    monedaSelect.addEventListener("change", () => {
        if (monedaSelect.value === "Soles") {
            simboloSpan.textContent = "S/";
        } else {
            simboloSpan.textContent = "$";
        }
    });

    // Inicializar el símbolo según la moneda seleccionada
    if (monedaSelect.value === "Soles") {
        simboloSpan.textContent = "S/";
    } else {
        simboloSpan.textContent = "$";
    }

    // Bloquear el campo de moneda
    monedaSelect.disabled = true;

    const tipoPagoSelect = document.getElementById("tipo_pago");
    const numeroOperacionInput = document.getElementById("numero_operacion");

    // Función para actualizar el estado del número de operación
    const actualizarEstadoNumeroOperacion = () => {
        console.log("Tipo de Pago seleccionado:", tipoPagoSelect.value); // Depuración
        if (tipoPagoSelect.value === "Efectivo") {
            numeroOperacionInput.disabled = true;
            numeroOperacionInput.value = "";
            numeroOperacionInput.placeholder = "No aplica para efectivo"; // Refuerzo visual
            console.log("Número de operación deshabilitado"); // Depuración
        } else {
            numeroOperacionInput.disabled = false;
            numeroOperacionInput.placeholder = "Ingrese el número de operación"; // Refuerzo visual
            console.log("Número de operación habilitado"); // Depuración
        }
    };

    // Enlazar el evento change al select de tipo de pago
    tipoPagoSelect.addEventListener("change", actualizarEstadoNumeroOperacion);

    // Inicializar el estado del número de operación al cargar la página
    actualizarEstadoNumeroOperacion();

    // Refuerzo adicional: verificar el estado cada vez que se interactúe con el formulario
    tipoPagoSelect.addEventListener("blur", actualizarEstadoNumeroOperacion);
});

// Idempotent script para la vista de pagos
(function () {
    if (window.__pagosInit) return;
    window.__pagosInit = true;

    const getSaldoDesdeServer = () => {
        // Preferimos una variable global inyectada desde Blade: window.SALDO_PENDIENTE
        if (typeof window.SALDO_PENDIENTE !== 'undefined') {
            const n = Number(window.SALDO_PENDIENTE);
            return isNaN(n) ? null : n;
        }
        return null;
    };

    function setMontoAutomatico() {
        try {
            const montoInput = document.getElementById('pago_monto');
            if (!montoInput) {
                console.warn('pago_monto no encontrado en el DOM.');
                return;
            }
            const saldo = getSaldoDesdeServer();
            if (saldo === null) {
                console.warn('Saldo no disponible o inválido en window.SALDO_PENDIENTE.');
                return;
            }
            montoInput.value = Number(saldo).toFixed(2);
            console.log('Monto a pagar actualizado automáticamente:', montoInput.value);
        } catch (err) {
            console.error('Error al setear monto automático:', err);
        }
    }

    function runInit() {
        setMontoAutomatico();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runInit);
    } else {
        runInit();
    }
})();

@endsection