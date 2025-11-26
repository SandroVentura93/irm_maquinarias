# Control de Stock para Cotizaciones

## Fecha de Implementación
25 de Noviembre de 2025

## Problema
Las cotizaciones estaban afectando el stock de productos al momento de su creación, lo cual no es correcto. El descuento en el inventario debe ejecutarse únicamente cuando la cotización sea convertida en un comprobante de pago válido (Boleta, Factura o Ticket).

## Solución Implementada

### 1. Identificación de Tipos de Comprobante
- **Cotizaciones**: Código SUNAT `CT`, ID tipo comprobante: `8`
- **Comprobantes que afectan stock**:
  - Factura: Código SUNAT `01`
  - Boleta de Venta: Código SUNAT `03`
  - Ticket de Máquina Registradora: Código SUNAT `12`

### 2. Cambios en VentaController.php

#### 2.1 Método `store()` (Línea ~750-790)
**Modificación**: Agregado control de descuento de stock basado en el tipo de comprobante.

```php
// Determinar si debe descontar stock
$tipoComprobanteDB = \App\Models\TipoComprobante::where('id_tipo_comprobante', $id_tipo_comprobante)->first();
$descuentaStock = false;
if ($tipoComprobanteDB) {
    $codigo = strtoupper($tipoComprobanteDB->codigo_sunat ?? '');
    // Solo descuentan stock: Factura (01), Boleta (03), Ticket (12)
    if (in_array($codigo, ['01', '03', '12'])) {
        $descuentaStock = true;
    }
}

// En el foreach de detalles:
if ($descuentaStock) {
    Producto::where('id_producto', $d['id_producto'])
        ->decrement('stock_actual', $d['cantidad']);
}
```

**Resultado**: Las cotizaciones NO descontarán stock al ser creadas.

#### 2.2 Método `guardarVenta()` (Línea ~385-430)
**Estado**: Ya estaba correctamente implementado. Verifica el código SUNAT y solo descuenta stock para comprobantes de venta válidos.

#### 2.3 Método `convertirCotizacion()` (Línea ~995)
**Estado**: Ya estaba correctamente implementado. Descuenta stock al convertir cotización a Factura/Boleta/Ticket.

#### 2.4 Método `convertirAFactura()` (Línea ~1200-1210)
**Modificación**: Agregado descuento de stock al convertir cotización a factura.

```php
// Descontar stock de los productos al convertir a factura
foreach ($venta->detalleVentas as $detalle) {
    Producto::where('id_producto', $detalle->id_producto)
        ->decrement('stock_actual', $detalle->cantidad);
}
```

#### 2.5 Método `convertirABoleta()` (Línea ~1260-1270)
**Modificación**: Agregado descuento de stock al convertir cotización a boleta.

```php
// Descontar stock de los productos al convertir a boleta
foreach ($venta->detalleVentas as $detalle) {
    Producto::where('id_producto', $detalle->id_producto)
        ->decrement('stock_actual', $detalle->cantidad);
}
```

#### 2.6 Método `cancel()` (Línea ~1120-1170)
**Modificación**: Agregada validación para solo revertir stock si NO es cotización.

```php
// Obtener tipo de comprobante para determinar si debe revertir stock
$tipoComprobanteDB = \App\Models\TipoComprobante::where('id_tipo_comprobante', $venta->id_tipo_comprobante)->first();
$revertirStock = false;

if ($tipoComprobanteDB) {
    $codigo = strtoupper($tipoComprobanteDB->codigo_sunat ?? '');
    // Solo revertir stock para comprobantes de venta: Factura (01), Boleta (03), Ticket (12)
    // NO revertir para Cotizaciones (CT)
    if (in_array($codigo, ['01', '03', '12'])) {
        $revertirStock = true;
    }
}

// Revertir stock solo si corresponde
if ($revertirStock) {
    foreach ($venta->detalleVentas as $detalle) {
        // ... código de reversión de stock
    }
}
```

**Resultado**: Al anular una cotización, NO se revertirá stock (porque nunca se descontó). Al anular una Factura/Boleta/Ticket, SÍ se revertirá el stock.

## Flujo Correcto Actual

### Crear Cotización
1. Usuario crea una cotización (tipo comprobante CT)
2. Se registran todos los datos de la venta
3. **NO se descuenta stock** ✅
4. Cotización queda en estado PENDIENTE

### Convertir Cotización a Comprobante
1. Usuario selecciona convertir cotización a Factura/Boleta/Ticket
2. Se actualiza el tipo de comprobante
3. Se genera nueva numeración según el tipo destino
4. **SE DESCUENTA el stock en este momento** ✅
5. Comprobante queda en estado PENDIENTE

### Anular Comprobante
1. **Si es Cotización**: Se anula sin afectar stock ✅
2. **Si es Factura/Boleta/Ticket**: Se anula y se revierte el stock ✅

## Archivos Modificados
- `app/Http/Controllers/VentaController.php`

## Pruebas Recomendadas
1. ✅ Crear cotización y verificar que stock NO cambia
2. ✅ Convertir cotización a factura y verificar que stock se descuenta
3. ✅ Convertir cotización a boleta y verificar que stock se descuenta
4. ✅ Anular cotización y verificar que stock NO cambia
5. ✅ Anular factura/boleta y verificar que stock se revierte

## Notas Técnicas
- Se utiliza el campo `codigo_sunat` de la tabla `tipo_comprobantes` para determinar el comportamiento
- Los logs de Laravel registran todas las operaciones de stock para auditoría
- La transacción de base de datos garantiza consistencia en caso de errores
