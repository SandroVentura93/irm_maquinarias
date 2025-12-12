<!-- Logo Component para PDFs -->
<div class="company-logo">
    @php
        // PRIORIDAD ABSOLUTA: logo.png desde public/images/
        $logoPath = public_path('images/logo.png');
        $logoExists = file_exists($logoPath);
        
        // Verificar que GD está disponible para procesar PNG
        $gdAvailable = extension_loaded('gd');
        
        // Crear base64 del PNG si existe y GD está disponible
        $logoBase64 = null;
        if ($logoExists && $gdAvailable) {
            try {
                $logoContent = file_get_contents($logoPath);
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoContent);
            } catch (Exception $e) {
                // Si hay error leyendo el archivo, usar fallback
                $logoBase64 = null;
            }
        }
        
        // Logo SVG de fallback (solo como última opción)
        $logoSvgFallback = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 100" style="background:#f39c12"><rect width="200" height="100" fill="#f39c12"/><circle cx="100" cy="50" r="35" fill="#2c3e50"/><text x="100" y="58" font-family="Arial" font-size="18" font-weight="bold" text-anchor="middle" fill="white">IRM</text><text x="100" y="75" font-family="Arial" font-size="8" text-anchor="middle" fill="white">MAQUINARIAS</text></svg>';
    @endphp
    
    @if($logoExists && $logoBase64 && $gdAvailable)
        <!-- 🥇 PRIORIDAD 1: Logo PNG desde public/images/logo.png -->
    <img src="{{ $logoBase64 }}" alt="IRM Maquinarias S.R.L." class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
    @elseif(isset($empresa['logo_base64']) && $empresa['logo_base64'])
        <!-- 🥈 PRIORIDAD 2: Logo desde configuración de empresa -->
    <img src="{{ $empresa['logo_base64'] }}" alt="{{ $empresa['razon_social'] ?? 'IRM Maquinarias S.R.L.' }}" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
    @else
        <!-- 🥉 PRIORIDAD 3: Logo SVG de fallback -->
    <img src="data:image/svg+xml;base64,{{ base64_encode($logoSvgFallback) }}" alt="IRM Maquinarias S.R.L." class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
    @endif
</div>

<!-- Estilos CSS para el logo -->
<style>
    .company-logo {
        width: 120px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .company-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>