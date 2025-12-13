@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header con Gradiente -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('monedas.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-info">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <h1 class="header-title mb-0">
                            <i class="fas fa-coins me-3"></i>
                            {{ $moneda->nombre }}
                        </h1>
                        <span class="currency-badge">
                            {{ $moneda->simbolo }}
                        </span>
                    </div>
                    <p class="header-subtitle">Vista detallada de la moneda</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('monedas.edit', $moneda->id_moneda) }}" class="btn-action-header">
                    <i class="fas fa-edit me-2"></i>
                    Editar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna Principal -->
        <div class="col-lg-8">
            <!-- Información General -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-info-circle"></i>
                    <span>Información General</span>
                </div>
                <div class="card-body-modern">
                    <div class="currency-display">
                        <div class="currency-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="currency-details">
                            <div class="currency-row">
                                <span class="currency-label">Nombre Completo:</span>
                                <span class="currency-value">{{ $moneda->nombre }}</span>
                            </div>
                            <div class="currency-row">
                                <span class="currency-label">Símbolo Monetario:</span>
                                <span class="currency-symbol-large">{{ $moneda->simbolo }}</span>
                            </div>
                            <div class="currency-row">
                                <span class="currency-label">Código ISO 4217:</span>
                                <span class="currency-iso-large">{{ $moneda->codigo_iso }}</span>
                            </div>
                            <div class="currency-row">
                                <span class="currency-label">ID del Sistema:</span>
                                <span class="currency-value">#{{ $moneda->id_moneda }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ejemplo de Uso -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-calculator"></i>
                    <span>Ejemplos de Formato</span>
                </div>
                <div class="card-body-modern">
                    <div class="format-examples">
                        <div class="format-item">
                            <div class="format-label">
                                <i class="fas fa-tag"></i>
                                Formato Simple
                            </div>
                            <div class="format-value">{{ $moneda->simbolo }} 1,000.00</div>
                        </div>
                        <div class="format-item">
                            <div class="format-label">
                                <i class="fas fa-receipt"></i>
                                En Factura
                            </div>
                            <div class="format-value">Total: {{ $moneda->simbolo }} 25,450.75</div>
                        </div>
                        <div class="format-item">
                            <div class="format-label">
                                <i class="fas fa-chart-line"></i>
                                Con Decimales
                            </div>
                            <div class="format-value">{{ $moneda->simbolo }} 99.99</div>
                        </div>
                        <div class="format-item">
                            <div class="format-label">
                                <i class="fas fa-code"></i>
                                Con Código ISO
                            </div>
                            <div class="format-value">{{ $moneda->codigo_iso }} 5,000.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Uso en el Sistema -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-database"></i>
                    <span>Uso en el Sistema</span>
                </div>
                <div class="card-body-modern">
                    @php
                        $totalVentas = \App\Models\Venta::where('id_moneda', $moneda->id_moneda)->count();
                        $totalCompras = \App\Models\Compra::where('id_moneda', $moneda->id_moneda)->count();
                        $montoVentas = \App\Models\Venta::where('id_moneda', $moneda->id_moneda)->sum('total');
                        $montoCompras = \App\Models\Compra::where('id_moneda', $moneda->id_moneda)->sum('total');
                    @endphp

                    <div class="usage-grid">
                        <div class="usage-card">
                            <div class="usage-icon usage-icon-sales">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="usage-info">
                                <div class="usage-value">{{ $totalVentas }}</div>
                                <div class="usage-label">Ventas Registradas</div>
                                <div class="usage-amount">{{ $moneda->simbolo }} {{ number_format($montoVentas, 2) }}</div>
                            </div>
                        </div>

                        <div class="usage-card">
                            <div class="usage-icon usage-icon-purchases">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="usage-info">
                                <div class="usage-value">{{ $totalCompras }}</div>
                                <div class="usage-label">Compras Registradas</div>
                                <div class="usage-amount">{{ $moneda->simbolo }} {{ number_format($montoCompras, 2) }}</div>
                            </div>
                        </div>

                        <div class="usage-card">
                            <div class="usage-icon usage-icon-total">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="usage-info">
                                <div class="usage-value">{{ $totalVentas + $totalCompras }}</div>
                                <div class="usage-label">Total Operaciones</div>
                                <div class="usage-amount">{{ $moneda->simbolo }} {{ number_format($montoVentas + $montoCompras, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    @if($totalVentas > 0 || $totalCompras > 0)
                    <div class="usage-note usage-note-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Esta moneda está siendo utilizada en el sistema. Ten cuidado al realizar cambios.</span>
                    </div>
                    @else
                    <div class="usage-note usage-note-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Esta moneda aún no ha sido utilizada en el sistema.</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Lateral -->
        <div class="col-lg-4">
            <!-- Acciones Rápidas -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-bolt"></i>
                    <span>Acciones Rápidas</span>
                </div>
                <div class="card-body-modern">
                    <div class="quick-actions">
                        <a href="{{ route('monedas.edit', $moneda->id_moneda) }}" class="quick-action-btn btn-edit">
                            <i class="fas fa-edit"></i>
                            <span>Editar Moneda</span>
                        </a>
                        @if(auth()->check() && auth()->user()->id_rol === 1)
                        <form action="{{ route('monedas.destroy', $moneda->id_moneda) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta moneda?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="quick-action-btn btn-delete">
                                <i class="fas fa-trash-alt"></i>
                                <span>Eliminar</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Vista Previa -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-eye"></i>
                    <span>Vista Previa</span>
                </div>
                <div class="card-body-modern">
                    <div class="preview-card">
                        <div class="preview-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="preview-content">
                            <div class="preview-name">{{ $moneda->nombre }}</div>
                            <div class="preview-details">
                                <span class="preview-symbol">{{ $moneda->simbolo }}</span>
                                <span class="preview-iso">{{ $moneda->codigo_iso }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline de Auditoría -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-history"></i>
                    <span>Información del Registro</span>
                </div>
                <div class="card-body-modern">
                    <div class="info-message">
                        <i class="fas fa-info-circle"></i>
                        <p>Esta moneda es parte de la configuración del sistema.</p>
                    </div>
                </div>
            </div>

            <!-- Información Técnica -->
            <div class="card-modern">
                <div class="card-header-gradient">
                    <i class="fas fa-cogs"></i>
                    <span>Información Técnica</span>
                </div>
                <div class="card-body-modern">
                    <div class="tech-info">
                        <div class="tech-item">
                            <span class="tech-label">Estándar:</span>
                            <span class="tech-value">ISO 4217</span>
                        </div>
                        <div class="tech-item">
                            <span class="tech-label">Tipo:</span>
                            <span class="tech-value">Moneda</span>
                        </div>
                        <div class="tech-item">
                            <span class="tech-label">Formato:</span>
                            <span class="tech-value">Decimal (2 dígitos)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header Moderno */
    .page-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 1;
    }

    .btn-back {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        font-size: 18px;
        flex-shrink: 0;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
        color: white;
    }

    .header-info {
        color: white;
        flex: 1;
    }

    .header-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        display: flex;
        align-items: center;
    }

    .header-subtitle {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .currency-badge {
        background: rgba(255, 255, 255, 0.9);
        color: #667eea;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 20px;
        font-weight: 700;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action-header {
        background: white;
        color: #667eea;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-action-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #667eea;
    }

    /* Tarjetas Modernas */
    .card-modern {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 25px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body-modern {
        padding: 30px;
    }

    /* Display de Moneda */
    .currency-display {
        display: flex;
        gap: 30px;
        align-items: center;
        padding: 30px;
        background: linear-gradient(135deg, #f0f3ff 0%, #e6efff 100%);
        border-radius: 16px;
    }

    .currency-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .currency-details {
        flex: 1;
    }

    .currency-row {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
    }

    .currency-row:last-child {
        border-bottom: none;
    }

    .currency-label {
        font-size: 14px;
        color: #667eea;
        font-weight: 600;
        min-width: 180px;
    }

    .currency-value {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
    }

    .currency-symbol-large {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 24px;
        font-weight: 700;
        display: inline-block;
    }

    .currency-iso-large {
        background: #667eea;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        display: inline-block;
    }

    /* Ejemplos de Formato */
    .format-examples {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .format-item {
        padding: 20px;
        background: #f7fafc;
        border-radius: 12px;
        border-left: 4px solid #667eea;
    }

    .format-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #667eea;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .format-value {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
    }

    /* Uso en el Sistema */
    .usage-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .usage-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f7fafc;
        border-radius: 12px;
    }

    .usage-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .usage-icon-sales {
        background: rgba(72, 187, 120, 0.1);
        color: #48bb78;
    }

    .usage-icon-purchases {
        background: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }

    .usage-icon-total {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .usage-info {
        flex: 1;
    }

    .usage-value {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .usage-label {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .usage-amount {
        font-size: 14px;
        color: #667eea;
        font-weight: 600;
        margin-top: 4px;
    }

    .usage-note {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border-radius: 12px;
        font-size: 14px;
    }

    .usage-note-warning {
        background: rgba(237, 137, 54, 0.1);
        color: #c05621;
        border-left: 4px solid #ed8936;
    }

    .usage-note-info {
        background: rgba(66, 153, 225, 0.1);
        color: #2c5282;
        border-left: 4px solid #4299e1;
    }

    /* Acciones Rápidas */
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        width: 100%;
    }

    .btn-edit {
        background: #4299e1;
        color: white;
    }

    .btn-edit:hover {
        background: #3182ce;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
    }

    .btn-delete {
        background: #fc8181;
        color: white;
    }

    .btn-delete:hover {
        background: #f56565;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }

    /* Vista Previa */
    .preview-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 16px;
        text-align: center;
    }

    .preview-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 36px;
        color: white;
        backdrop-filter: blur(10px);
    }

    .preview-content {
        color: white;
    }

    .preview-name {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .preview-details {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
    }

    .preview-symbol {
        background: rgba(255, 255, 255, 0.9);
        color: #667eea;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 24px;
        font-weight: 700;
    }

    .preview-iso {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        backdrop-filter: blur(10px);
    }

    /* Timeline de Auditoría */
    .audit-timeline {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .audit-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }

    .audit-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }

    .audit-icon-success {
        background: rgba(72, 187, 120, 0.1);
        color: #48bb78;
    }

    .audit-icon-info {
        background: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }

    .audit-content {
        flex: 1;
    }

    .audit-label {
        font-size: 12px;
        color: #a0aec0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .audit-value {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .audit-time {
        font-size: 12px;
        color: #718096;
    }

    /* Mensaje de Información */
    .info-message {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #f0f3ff;
        border-radius: 12px;
        color: #667eea;
        font-size: 14px;
    }

    .info-message i {
        font-size: 24px;
    }

    .info-message p {
        margin: 0;
    }

    /* Información Técnica */
    .tech-info {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .tech-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #f7fafc;
        border-radius: 8px;
    }

    .tech-label {
        font-size: 13px;
        color: #718096;
        font-weight: 600;
    }

    .tech-value {
        font-size: 14px;
        color: #2d3748;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-modern {
            padding: 20px;
        }

        .header-content {
            flex-direction: column;
        }

        .header-title {
            font-size: 22px;
        }

        .header-actions {
            width: 100%;
        }

        .btn-action-header {
            width: 100%;
            justify-content: center;
        }

        .card-body-modern {
            padding: 20px;
        }

        .currency-display {
            flex-direction: column;
            text-align: center;
        }

        .currency-row {
            flex-direction: column;
            text-align: center;
        }

        .currency-label {
            min-width: auto;
            margin-bottom: 8px;
        }

        .format-examples {
            grid-template-columns: 1fr;
        }

        .usage-grid {
            grid-template-columns: 1fr;
        }

        .preview-details {
            flex-direction: column;
        }
    }
</style>
@endsection
