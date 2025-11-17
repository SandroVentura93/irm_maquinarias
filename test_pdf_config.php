Route::get('/test-pdf-config', function () {
    echo "<h2>🔍 TEST DE CONFIGURACIÓN PDF</h2>";
    
    $controller = new \App\Http\Controllers\PdfController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConfiguracionTipoComprobante');
    $method->setAccessible(true);
    
    // Simular diferentes tipos de comprobante según el seeder
    $tiposAProbar = [
        (object) ['codigo_sunat' => '01', 'descripcion' => 'Factura'],
        (object) ['codigo_sunat' => '03', 'descripcion' => 'Boleta de Venta'],
        (object) ['codigo_sunat' => '07', 'descripcion' => 'Nota de Crédito'],
        (object) ['codigo_sunat' => '08', 'descripcion' => 'Nota de Débito'],
        (object) ['codigo_sunat' => '09', 'descripcion' => 'Guía de Remisión'],
        (object) ['codigo_sunat' => '12', 'descripcion' => 'Ticket de Máquina Registradora'],
        (object) ['codigo_sunat' => '14', 'descripcion' => 'Recibo por Honorarios'],
        (object) ['codigo_sunat' => 'CT', 'descripcion' => 'Cotización'],
    ];
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Código SUNAT</th><th>Descripción</th><th>Template</th><th>Estado</th></tr>";
    
    foreach ($tiposAProbar as $tipo) {
        $config = $method->invoke($controller, $tipo);
        $estado = $config ? "✅ OK" : "❌ ERROR";
        $template = $config ? $config['template'] : 'N/A';
        
        echo "<tr>";
        echo "<td>{$tipo->codigo_sunat}</td>";
        echo "<td>{$tipo->descripcion}</td>";
        echo "<td>{$template}</td>";
        echo "<td>{$estado}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>🎯 RESULTADO:</h3>";
    echo "<p>Si todos muestran ✅ OK, la configuración está correcta.</p>";
});