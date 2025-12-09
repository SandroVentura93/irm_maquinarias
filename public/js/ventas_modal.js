```javascript
const pagosData = JSON.parse(pagosAttr);
const pagosFiltrados = pagosData.filter(function(pago) {
    return pago.moneda === monedaVenta;
});
if (!Array.isArray(pagosFiltrados) || pagosFiltrados.length === 0) {
    const li = document.createElement('li');
    li.className = 'list-group-item text-muted';
    li.textContent = 'No hay pagos registrados en esta moneda.';
    historialPagos.appendChild(li);
} else {
    pagosFiltrados.forEach(function(pago) {
        const montoSoles = numberSafe(pago.monto);
        const montoDolares = tipoCambio > 0 ? (montoSoles / tipoCambio) : 0;
        const label = monedaVenta === 'USD' ? '$ ' + montoDolares.toFixed(2) : 'S/ ' + montoSoles.toFixed(2);
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = '<div><strong>' + (pago.metodo || '—') + '</strong> - ' + (pago.fecha || '—') + '</div>' +
            '<div class="text-end">' +
            '<span class="badge bg-success">' + label + '</span>' +
            '</div>';
        historialPagos.appendChild(li);
    });
}
```