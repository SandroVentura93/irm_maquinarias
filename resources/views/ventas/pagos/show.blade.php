@extends('layouts.dashboard')

@section('content')
<div class="container mt-4">
    <h1 class="text-primary">Registrar Pago para la Venta #{{ $id }}</h1>

    <!-- Información del saldo -->
    <div class="alert alert-info mb-3">
        <h6 class="mb-0">
            <strong>Saldo Pendiente:</strong> 
            <span class="fw-bold fs-5">{{ $simbolo }} {{ number_format($saldo, 2) }}</span>
        </h6>
    </div>

    <form id="formPago" method="POST" action="{{ route('ventas.pago', ['venta' => $id]) }}">
        @csrf
        <input type="hidden" name="venta_id" value="{{ $id }}">

        <div class="mb-3">
            <label for="pago_monto" class="form-label">Monto a Pagar</label>
            <div class="input-group">
                <span class="input-group-text">{{ $simbolo }}</span>
                <input type="number" step="0.01" class="form-control" id="pago_monto" name="monto" required value="{{ number_format($saldo, 2) }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="pago_moneda" class="form-label">Moneda del Pago</label>
            <select class="form-select" id="pago_moneda" name="pago_moneda" required disabled>
                <option value="Soles" {{ $moneda === 'Soles' ? 'selected' : '' }}>Soles</option>
                <option value="Dolares" {{ $moneda === 'Dolares' ? 'selected' : '' }}>Dólares</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo_pago" class="form-label">Tipo de Pago</label>
            <select class="form-select" id="tipo_pago" name="tipo_pago" required>
                <option value="Efectivo">Efectivo</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Yape">Yape</option>
                <option value="Plin">Plin</option>
            </select>
        </div>

        <div class="mb-3" id="numeroOperacionContainer">
            <label for="numero_operacion" class="form-label">Número de Operación</label>
            <input type="text" class="form-control" id="numero_operacion" name="numero_operacion" required>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Registrar Pago</button>
        </div>
    </form>

    <!-- Historial de Pagos -->
    <div class="mt-4">
        <h6>Historial de Pagos</h6>
        <ul class="list-group">
            @foreach ($venta->pagos as $pago)
                <li class="list-group-item">
                    {{ $pago->fecha }} - {{ $pago->moneda }} {{ number_format($pago->monto, 2) }}
                </li>
            @endforeach
        </ul>
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

<script>
(function () {
    if (window.__pagosDebugInit) return;
    window.__pagosDebugInit = true;

    console.log('Iniciando depuración del script de pagos...');

    const montoInput = document.getElementById('pago_monto');
    if (!montoInput) {
        console.error('El campo "Monto a Pagar" no se encontró en el DOM.');
        return;
    }

    console.log('Campo "Monto a Pagar" encontrado:', montoInput);

    if (typeof window.SALDO_PENDIENTE === 'undefined') {
        console.error('La variable global "SALDO_PENDIENTE" no está definida.');
        return;
    }

    console.log('Saldo pendiente obtenido desde la variable global:', window.SALDO_PENDIENTE);

    const saldo = Number(window.SALDO_PENDIENTE);
    if (isNaN(saldo)) {
        console.error('El saldo pendiente no es un número válido:', window.SALDO_PENDIENTE);
        return;
    }

    montoInput.value = saldo.toFixed(2);
    console.log('Monto a pagar actualizado automáticamente:', montoInput.value);
})();
</script>
@endsection