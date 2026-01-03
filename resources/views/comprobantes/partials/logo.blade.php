<!-- Logo Component para PDFs (sin fuentes/recursos remotos) -->
<div class="company-logo">
    @php
        // Intentar cargar logo local desde public/images/logo.png
        $logoPath = public_path('images/logo.png');
        $logoBase64 = null;
        if (is_file($logoPath)) {
            try {
                $logoContent = file_get_contents($logoPath);
                if ($logoContent !== false) {
                    $logoBase64 = 'data:image/png;base64,' . base64_encode($logoContent);
                }
            } catch (\Exception $e) {
                $logoBase64 = null;
            }
        }
        // Logo desde configuración (ya viene en base64 si existe)
        $empresaLogo = isset($empresa['logo_base64']) ? $empresa['logo_base64'] : null;
    @endphp

    @if($logoBase64)
        <img src="{{ $logoBase64 }}" alt="IRM Maquinarias S.R.L." style="max-width: 100%; max-height: 100%; object-fit: contain;">
    @elseif($empresaLogo)
        <img src="{{ $empresaLogo }}" alt="{{ $empresa['razon_social'] ?? 'IRM Maquinarias S.R.L.' }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
    @else
        <!-- Fallback ultra simple para evitar bloqueos en DomPDF -->
        <div style="
            width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
            background: #f8f9fa; border: 2px solid #2c5aa0; color: #2c5aa0; font-weight: bold; font-size: 11px;">
            IRM Maquinarias S.R.L.
        </div>
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
    .company-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }
</style>