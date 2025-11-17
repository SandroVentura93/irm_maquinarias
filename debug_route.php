Route::get('/debug-comprobantes', function () {
    try {
        // Obtener todos los tipos de comprobante de la base de datos
        $tiposDB = DB::table('tipo_comprobantes')->get();
        
        echo "<h2>Tipos de Comprobante en Base de Datos:</h2>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Código SUNAT</th><th>Descripción</th></tr>";
        
        foreach ($tiposDB as $tipo) {
            echo "<tr>";
            echo "<td>{$tipo->id}</td>";
            echo "<td>{$tipo->codigo_sunat}</td>";
            echo "<td>{$tipo->descripcion}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Verificar la venta #26 específicamente
        echo "<h2>Venta #26 - Análisis:</h2>";
        $venta = \App\Models\Venta::with('tipoComprobante')->find(26);
        
        if ($venta) {
            echo "<p><strong>ID Venta:</strong> {$venta->id_venta}</p>";
            echo "<p><strong>ID Tipo Comprobante:</strong> {$venta->id_tipo_comprobante}</p>";
            echo "<p><strong>Tipo Comprobante Descripción:</strong> " . ($venta->tipoComprobante->descripcion ?? 'N/A') . "</p>";
            echo "<p><strong>Código SUNAT:</strong> " . ($venta->tipoComprobante->codigo_sunat ?? 'N/A') . "</p>";
            
            // Probar el PdfController
            $controller = new \App\Http\Controllers\PdfController();
            $reflection = new ReflectionClass($controller);
            $method = $reflection->getMethod('getConfiguracionTipoComprobante');
            $method->setAccessible(true);
            
            $config = $method->invoke($controller, $venta->tipoComprobante);
            
            echo "<h3>Configuración que debería usar:</h3>";
            if ($config) {
                echo "<pre>" . json_encode($config, JSON_PRETTY_PRINT) . "</pre>";
            } else {
                echo "<p style='color: red;'>❌ No se encontró configuración</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Venta #26 no encontrada</p>";
        }
        
        return null;
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});