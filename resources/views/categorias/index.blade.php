@extends('layouts.dashboard')

@section('content')
<div class="container-fluid modern-container">
    <!-- Modern Header -->
    <div class="page-header mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="page-icon me-3">
                    <i class="fas fa-tags"></i>
                </div>
                <div>
                    <h2 class="page-title mb-0">Gestión de Categorías</h2>
                    <p class="page-subtitle mb-0">Organiza y administra las categorías de productos</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('categorias.create') }}" class="btn btn-success btn-modern">
                    <i class="fas fa-plus-circle me-2"></i>Nueva Categoría
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success modern-alert alert-dismissible fade show">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <strong>¡Éxito!</strong>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card stats-primary">
                <div class="stats-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $categorias->where('activo', 1)->count() }}</h3>
                    <p class="stats-label">Categorías Activas</p>
                    <div class="stats-trend">
                        <i class="fas fa-arrow-up me-1"></i>
                        <small>En uso</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-secondary">
                <div class="stats-icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $categorias->where('activo', 0)->count() }}</h3>
                    <p class="stats-label">Categorías Inactivas</p>
                    <div class="stats-trend">
                        <i class="fas fa-minus me-1"></i>
                        <small>Deshabilitadas</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-info">
                <div class="stats-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $categorias->count() }}</h3>
                    <p class="stats-label">Total Categorías</p>
                    <div class="stats-trend">
                        <i class="fas fa-chart-bar me-1"></i>
                        <small>En el sistema</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-success">
                <div class="stats-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $categorias->count() > 0 ? round(($categorias->where('activo', 1)->count() / $categorias->count()) * 100) : 0 }}%</h3>
                    <p class="stats-label">Tasa de Uso</p>
                    <div class="stats-trend">
                        <i class="fas fa-chart-line me-1"></i>
                        <small>Porcentaje activo</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Categorías -->
    <div class="card modern-card">
        <div class="card-header modern-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2 text-primary"></i>
                        Lista de Categorías
                    </h5>
                    <small class="text-muted">Administra las categorías de productos</small>
                </div>
                <div class="table-actions">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control modern-input" id="searchCategories" placeholder="🔍 Buscar categoría...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="categoriesTable">
                    <thead class="modern-thead">
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ID</th>
                            <th><i class="fas fa-tag me-1"></i>Nombre</th>
                            <th><i class="fas fa-align-left me-1"></i>Descripción</th>
                            <th><i class="fas fa-toggle-on me-1"></i>Estado</th>
                            <th><i class="fas fa-calendar me-1"></i>Creación</th>
                            <th class="text-center"><i class="fas fa-cogs me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categorias as $categoria)
                            <tr class="category-row" data-category-name="{{ strtolower($categoria->nombre) }}" data-category-desc="{{ strtolower($categoria->descripcion) }}">
                                <td>
                                    <div class="id-badge">
                                        <span class="badge-id">#{{ $categoria->id_categoria }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="category-info">
                                        <div class="category-name">{{ $categoria->nombre }}</div>
                                        <small class="category-code text-muted">{{ strtoupper(str_replace(' ', '_', $categoria->nombre)) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="category-description">
                                        {{ $categoria->descripcion }}
                                    </div>
                                </td>
                                <td>
                                    <div class="status-container">
                                        <span class="status-badge {{ $categoria->activo ? 'status-active' : 'status-inactive' }}">
                                            <i class="fas fa-{{ $categoria->activo ? 'check-circle' : 'times-circle' }} me-1"></i>
                                            {{ $categoria->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div class="date-main">{{ $categoria->created_at ? $categoria->created_at->format('d/m/Y') : 'N/A' }}</div>
                                        <small class="date-time text-muted">{{ $categoria->created_at ? $categoria->created_at->format('H:i') : '' }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('categorias.show', $categoria->id_categoria) }}" 
                                           class="btn btn-info btn-modern-sm" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categorias.edit', $categoria->id_categoria) }}" 
                                           class="btn btn-warning btn-modern-sm" title="Editar categoría">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('categorias.destroy', $categoria->id_categoria) }}" 
                                              method="POST" style="display:inline;" 
                                              onsubmit="return confirmDelete('{{ $categoria->nombre }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-modern-sm" title="Eliminar categoría">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay categorías registradas</h5>
                                        <p class="text-muted">Comienza creando tu primera categoría para organizar los productos</p>
                                        <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-modern">
                                            <i class="fas fa-plus me-2"></i>Crear Primera Categoría
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($categorias->count() > 0)
        <div class="card-footer modern-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="table-info">
                    <i class="fas fa-info-circle text-muted me-2"></i>
                    <small class="text-muted">
                        Mostrando {{ $categorias->count() }} categorías en total
                    </small>
                </div>
                <div class="table-actions">
                    <button class="btn btn-outline-secondary btn-sm me-2" onclick="exportCategories()">
                        <i class="fas fa-download me-1"></i>Exportar
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Imprimir
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Estilos modernos para categorías */
.modern-container {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.page-header {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
}

.page-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.page-subtitle {
    color: #718096;
    font-size: 16px;
}

.btn-modern {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Estadísticas modernas */
.stats-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    border: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stats-card.stats-primary::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stats-card.stats-secondary::before { background: linear-gradient(135deg, #a8a8a8 0%, #6c757d 100%); }
.stats-card.stats-info::before { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stats-card.stats-success::before { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    margin-bottom: 15px;
}

.stats-primary .stats-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stats-secondary .stats-icon { background: linear-gradient(135deg, #a8a8a8 0%, #6c757d 100%); }
.stats-info .stats-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stats-success .stats-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

.stats-number {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 5px;
    color: #2d3748;
}

.stats-label {
    font-size: 14px;
    color: #718096;
    margin-bottom: 10px;
}

.stats-trend {
    font-size: 12px;
    color: #4a5568;
}

/* Cards modernos */
.modern-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-2px);
}

.modern-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 25px;
}

.modern-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 15px 25px;
}

/* Inputs modernos */
.modern-input {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.modern-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

/* Tabla moderna */
.modern-table {
    border: none;
}

.modern-thead {
    background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
    color: white;
}

.modern-thead th {
    border: none;
    padding: 15px 12px;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.category-row {
    transition: all 0.3s ease;
    border: none;
}

.category-row:hover {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    transform: scale(1.01);
}

.category-row td {
    padding: 15px 12px;
    vertical-align: middle;
    border-top: 1px solid #e2e8f0;
}

.id-badge {
    text-align: center;
}

.badge-id {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 12px;
}

.category-info {
    display: flex;
    flex-direction: column;
}

.category-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 15px;
    margin-bottom: 2px;
}

.category-code {
    font-size: 11px;
    color: #a0aec0;
    font-family: monospace;
}

.category-description {
    color: #4a5568;
    font-size: 14px;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.status-container {
    text-align: center;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
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

.date-info {
    text-align: center;
}

.date-main {
    font-weight: 600;
    color: #2d3748;
    font-size: 13px;
}

.date-time {
    font-size: 11px;
    color: #a0aec0;
}

.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.btn-modern-sm {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.3s ease;
}

.btn-modern-sm:hover {
    transform: translateY(-2px) scale(1.1);
}

.empty-state {
    padding: 40px 20px;
    text-align: center;
}

/* Alertas modernas */
.modern-alert {
    border: none;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid;
    display: flex;
    align-items: center;
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 18px;
}

.alert-content {
    flex: 1;
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

.modern-card, .stats-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 20px;
        flex-direction: column;
        text-align: center;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .header-actions {
        margin-top: 15px;
    }
    
    .stats-card {
        margin-bottom: 15px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 8px;
    }
    
    .category-description {
        max-width: 150px;
    }
}
</style>

<script>
// JavaScript para la gestión de categorías
document.addEventListener('DOMContentLoaded', function() {
    // Función de búsqueda
    const searchInput = document.getElementById('searchCategories');
    const clearButton = document.getElementById('clearSearch');
    const tableRows = document.querySelectorAll('.category-row');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const categoryName = row.getAttribute('data-category-name');
                const categoryDesc = row.getAttribute('data-category-desc');
                
                if (categoryName.includes(searchTerm) || categoryDesc.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Mostrar/ocultar botón de limpiar
            clearButton.style.display = searchTerm ? 'block' : 'none';
        });
    }
    
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            tableRows.forEach(row => {
                row.style.display = '';
            });
            this.style.display = 'none';
            searchInput.focus();
        });
    }
    
    // Animaciones al cargar
    const cards = document.querySelectorAll('.modern-card, .stats-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Tooltips
    const tooltips = document.querySelectorAll('[title]');
    tooltips.forEach(element => {
        element.setAttribute('data-bs-toggle', 'tooltip');
    });
});

// Confirmación de eliminación
function confirmDelete(categoryName) {
    return confirm(`¿Está seguro de que desea eliminar la categoría "${categoryName}"?\n\nEsta acción no se puede deshacer.`);
}

// Exportar categorías (simulado)
function exportCategories() {
    alert('Función de exportación en desarrollo...');
}

// Función de impresión
function printCategories() {
    window.print();
}
</script>
@endsection