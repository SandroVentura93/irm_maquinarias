@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
    <!-- Hero Header -->
    <div class="hero-header mb-4">
        <div class="hero-background"></div>
        <div class="hero-content">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="hero-icon me-4">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="hero-text">
                        <h1 class="hero-title">{{ $categoria->nombre }}</h1>
                        <p class="hero-subtitle">
                            <i class="fas fa-info-circle me-2"></i>
                            Detalles completos de la categoría
                        </p>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('categorias.edit', $categoria->id_categoria) }}" class="btn btn-warning btn-hero me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary btn-hero">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card info-card mb-4">
                <div class="card-header info-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon me-3">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Información de la Categoría</h5>
                            <small class="text-muted">Datos principales y configuración</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-hashtag text-primary me-2"></i>
                                    ID de Categoría
                                </label>
                                <div class="info-value">
                                    <span class="badge-id">#{{ $categoria->id_categoria }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-toggle-{{ $categoria->activo ? 'on' : 'off' }} text-{{ $categoria->activo ? 'success' : 'danger' }} me-2"></i>
                                    Estado
                                </label>
                                <div class="info-value">
                                    <span class="status-badge {{ $categoria->activo ? 'status-active' : 'status-inactive' }}">
                                        <i class="fas fa-{{ $categoria->activo ? 'check-circle' : 'times-circle' }} me-1"></i>
                                        {{ $categoria->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-tag text-info me-2"></i>
                                    Nombre de la Categoría
                                </label>
                                <div class="info-value">
                                    <div class="category-name-display">{{ $categoria->nombre }}</div>
                                    <small class="category-code">{{ strtoupper(str_replace(' ', '_', $categoria->nombre)) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-align-left text-secondary me-2"></i>
                                    Descripción
                                </label>
                                <div class="info-value">
                                    <div class="description-display">
                                        {{ $categoria->descripcion ?: 'Sin descripción disponible' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lg-4">
            <!-- Estadísticas -->
            <div class="card stats-sidebar mb-4">
                <div class="card-header stats-sidebar-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Estadísticas
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="stat-item">
                        <div class="stat-icon stat-primary">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $categoria->created_at ? $categoria->created_at->format('M Y') : 'N/A' }}</div>
                            <div class="stat-label">Creado</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon stat-info">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $categoria->updated_at ? $categoria->updated_at->diffForHumans() : 'N/A' }}</div>
                            <div class="stat-label">Actualizado</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card actions-card mb-4">
                <div class="card-header actions-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('categorias.edit', $categoria->id_categoria) }}" class="btn btn-warning btn-action">
                            <i class="fas fa-edit me-2"></i>
                            Editar Categoría
                        </a>
                        @if(auth()->check() && auth()->user()->id_rol === 1)
                        <form action="{{ route('categorias.destroy', $categoria->id_categoria) }}" method="POST" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-action w-100">
                                <i class="fas fa-trash me-2"></i>
                                Eliminar Categoría
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card system-info-card">
                <div class="card-header system-info-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="system-info-item">
                        <small class="text-muted">ID:</small>
                        <code>{{ $categoria->id_categoria }}</code>
                    </div>
                    <div class="system-info-item">
                        <small class="text-muted">Creado:</small>
                        <span>{{ $categoria->created_at ? $categoria->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                    <div class="system-info-item">
                        <small class="text-muted">Modificado:</small>
                        <span>{{ $categoria->updated_at ? $categoria->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos modernos para la vista show de categorías */
.modern-container {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Hero Header */
.hero-header {
    position: relative;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0.05;
}

.hero-content {
    position: relative;
    padding: 40px 30px;
    z-index: 1;
}

.hero-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: white;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.hero-title {
    font-size: 36px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 5px;
}

.hero-subtitle {
    font-size: 16px;
    color: #718096;
    margin-bottom: 0;
}

.btn-hero {
    padding: 12px 25px;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Cards de información */
.info-card, .stats-sidebar, .actions-card, .system-info-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
}

.info-header, .stats-sidebar-header, .actions-header, .system-info-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 25px;
    border-radius: 15px 15px 0 0;
}

.header-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

/* Grupos de información */
.info-group {
    margin-bottom: 25px;
}

.info-label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
}

.info-value {
    padding: 12px 15px;
    background: #f8fafc;
    border-radius: 10px;
    border-left: 4px solid #e2e8f0;
}

.badge-id {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    display: inline-block;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-active {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.status-inactive {
    background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);
    color: white;
}

.category-name-display {
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 5px;
}

.category-code {
    font-family: monospace;
    background: #edf2f7;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    color: #4a5568;
}

.description-display {
    font-size: 16px;
    line-height: 1.6;
    color: #4a5568;
    padding: 10px 0;
}

/* Sidebar de estadísticas */
.stat-item {
    display: flex;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 16px;
}

.stat-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-success { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); }
.stat-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.stat-number {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    font-size: 13px;
    color: #718096;
}

/* Botones de acción */
.btn-action {
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Información del sistema */
.system-info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
}

.system-info-item:last-child {
    border-bottom: none;
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.info-card, .stats-sidebar, .actions-card, .system-info-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content {
        padding: 25px 20px;
    }
    
    .hero-title {
        font-size: 28px;
    }
    
    .hero-actions {
        margin-top: 20px;
    }
    
    .hero-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>

<script>
// Confirmación de eliminación
function confirmDelete() {
    return confirm('¿Está seguro de que desea eliminar esta categoría?\n\nEsta acción no se puede deshacer y afectará a todos los productos relacionados.');
}

// Animaciones al cargar
document.addEventListener('DOMContentLoaded', function() {
    // Fade in de las cards
    const cards = document.querySelectorAll('.info-card, .stats-sidebar, .actions-card, .system-info-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection