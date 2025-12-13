@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Mensajes de éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Moderno -->
    <div class="clientes-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="header-info">
                    <h1 class="header-title">Gestión de Clientes</h1>
                    <p class="header-subtitle">Administra tu base de datos de clientes</p>
                </div>
            </div>
            <div class="header-stats">
                <div class="stat-card">
                    <div class="stat-value">{{ $clientes->total() ?? 0 }}</div>
                    <div class="stat-label">Total Clientes</div>
                </div>
                <div class="stat-card">
                    @php
                        $totalActivos = \App\Models\Cliente::where('activo', true)->count();
                    @endphp
                    <div class="stat-value">{{ $totalActivos }}</div>
                    <div class="stat-label">Activos</div>
                </div>
                <div class="stat-card">
                    @php
                        $nuevosEsteMes = \App\Models\Cliente::where('created_at', '>=', now()->startOfMonth())->count();
                    @endphp
                    <div class="stat-value">{{ $nuevosEsteMes }}</div>
                    <div class="stat-label">Nuevos (Mes)</div>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('clientes.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus me-2"></i>
                    Nuevo Cliente
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda Avanzados -->
    <div class="filters-section">
        <form method="GET" action="{{ route('clientes.index') }}" class="filters-form">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Buscar por nombre, documento..." 
                           value="{{ request('search') }}">
                    <button type="button" class="search-clear" onclick="clearSearch()" style="{{ request('search') ? '' : 'display: none;' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="filters-container">
                <div class="filter-group">
                    <select name="tipo_documento" class="filter-select">
                        <option value="">Tipo Documento</option>
                        <option value="DNI" {{ request('tipo_documento') == 'DNI' ? 'selected' : '' }}>DNI</option>
                        <option value="RUC" {{ request('tipo_documento') == 'RUC' ? 'selected' : '' }}>RUC</option>
                        <option value="CE" {{ request('tipo_documento') == 'CE' ? 'selected' : '' }}>Carnet Extranjería</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="activo" class="filter-select">
                        <option value="">Estado</option>
                        <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter me-1"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn-clear">
                        <i class="fas fa-eraser me-1"></i>
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
        
        <!-- Controles de Vista -->
        <div class="view-controls">
            <div class="view-toggle">
                <button type="button" class="view-btn active" data-view="cards" title="Vista de tarjetas">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" class="view-btn" data-view="table" title="Vista de tabla">
                    <i class="fas fa-list"></i>
                </button>
            </div>
            
            <div class="results-info">
                <span class="results-count">
                    Mostrando {{ $clientes->firstItem() ?? 0 }} - {{ $clientes->lastItem() ?? 0 }} 
                    de {{ $clientes->total() ?? 0 }} clientes
                    @if(request('search'))
                        para "{{ request('search') }}"
                    @endif
                </span>
            </div>
        </div>
    </div>
    <!-- Vista de Tarjetas (Por defecto) -->
    <div class="clientes-container cards-view" id="cardsView">
        @if($clientes->count() > 0)
            <div class="clientes-grid">
                @foreach ($clientes as $cliente)
                <div class="cliente-card">
                    <!-- Badge de Estado -->
                    <div class="cliente-badge estado-{{ $cliente->activo ? 'activo' : 'inactivo' }}">
                        @if($cliente->activo)
                            <i class="fas fa-check-circle me-1"></i>
                            Activo
                        @else
                            <i class="fas fa-times-circle me-1"></i>
                            Inactivo
                        @endif
                    </div>

                    <!-- Avatar del Cliente -->
                    <div class="cliente-avatar">
                        <div class="avatar-circle">
                            {{ substr($cliente->nombre ?? 'N/A', 0, 2) }}
                        </div>
                    </div>

                    <!-- Información Principal -->
                    <div class="cliente-info">
                        <h3 class="cliente-name">
                            {{ $cliente->nombre }}
                        </h3>
                        <div class="cliente-document">
                            <span class="document-type">{{ $cliente->tipo_documento }}</span>
                            <span class="document-number">{{ $cliente->numero_documento }}</span>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="cliente-contact">
                        @if($cliente->telefono)
                        <div class="contact-item">
                            <i class="fas fa-phone contact-icon"></i>
                            <span class="contact-text">{{ $cliente->telefono }}</span>
                        </div>
                        @endif
                        
                        @if($cliente->correo)
                        <div class="contact-item">
                            <i class="fas fa-envelope contact-icon"></i>
                            <span class="contact-text">{{ $cliente->correo }}</span>
                        </div>
                        @endif
                        
                        @if($cliente->direccion)
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                            <span class="contact-text">{{ Str::limit($cliente->direccion, 30) }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Información Adicional -->
                    <div class="cliente-meta">
                        <div class="meta-item">
                            <span class="meta-label">Cliente desde:</span>
                            <span class="meta-value">{{ $cliente->created_at ? $cliente->created_at->format('M Y') : 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">ID:</span>
                            <span class="meta-value">#{{ $cliente->id_cliente }}</span>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="cliente-actions">
                        <a href="{{ route('clientes.show', $cliente->id_cliente) }}" class="action-btn view-btn" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="action-btn edit-btn" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="action-btn delete-btn" 
                                onclick="confirmDelete({{ $cliente->id_cliente }}, '{{ $cliente->nombre }}')" 
                                title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="empty-title">No hay clientes registrados</h3>
                <p class="empty-description">Comienza agregando tu primer cliente para gestionar tu base de datos.</p>
                <a href="{{ route('clientes.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus me-2"></i>
                    Crear Primer Cliente
                </a>
            </div>
        @endif
    </div>

    <!-- Vista de Tabla (Oculta por defecto) -->
    <div class="clientes-container table-view" id="tableView" style="display: none;">
        <div class="table-responsive">
            <table class="clientes-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>ID</th>
                        <th><i class="fas fa-id-card me-1"></i>Documento</th>
                        <th><i class="fas fa-user me-1"></i>Cliente</th>
                        <th><i class="fas fa-phone me-1"></i>Contacto</th>
                        <th><i class="fas fa-toggle-on me-1"></i>Estado</th>
                        <th><i class="fas fa-calendar me-1"></i>Registro</th>
                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                    <tr class="table-row">
                        <td>
                            <span class="table-id">#{{ $cliente->id_cliente }}</span>
                        </td>
                        <td>
                            <div class="document-info">
                                <span class="document-type-badge">{{ $cliente->tipo_documento }}</span>
                                <span class="document-number">{{ $cliente->numero_documento }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="client-info">
                                <div class="client-name">
                                    {{ $cliente->nombre }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                @if($cliente->telefono)
                                <div class="contact-detail">
                                    <i class="fas fa-phone contact-icon-small"></i>
                                    {{ $cliente->telefono }}
                                </div>
                                @endif
                                @if($cliente->correo)
                                <div class="contact-detail">
                                    <i class="fas fa-envelope contact-icon-small"></i>
                                    {{ Str::limit($cliente->correo, 25) }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="clasificacion-badge estado-{{ $cliente->activo ? 'activo' : 'inactivo' }}">
                                {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <span class="date-info">{{ $cliente->created_at ? $cliente->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('clientes.show', $cliente->id_cliente) }}" class="table-action-btn view" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="table-action-btn edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="table-action-btn delete" 
                                        onclick="confirmDelete({{ $cliente->id_cliente }}, '{{ $cliente->nombre }}')" 
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if($clientes->hasPages())
    <div class="pagination-container">
        {{ $clientes->appends(request()->query())->links('custom-pagination') }}
    </div>
    @endif

    <!-- Modal de Confirmación para Eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">¿Estás seguro de que deseas eliminar el cliente <strong id="clienteName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Esta acción no se puede deshacer.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    @if(auth()->check() && auth()->user()->id_rol === 1)
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>
                            Eliminar Cliente
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos CSS Modernos -->
<style>
:root {
    --primary-color: #667eea;
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --info-color: #17a2b8;
    --light-bg: #f8f9fa;
    --dark-text: #2d3748;
    --muted-text: #718096;
    --border-color: #e2e8f0;
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.15);
    --border-radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Header Moderno */
.clientes-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    backdrop-filter: blur(10px);
}

.header-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header-subtitle {
    margin: 0;
    opacity: 0.9;
    font-size: 1rem;
}

.header-stats {
    display: flex;
    gap: 1rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.15);
    padding: 1rem;
    border-radius: 10px;
    text-align: center;
    min-width: 80px;
    backdrop-filter: blur(10px);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 0.75rem;
    opacity: 0.9;
    margin-top: 0.25rem;
}

.btn-primary-modern {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    border: 2px solid rgba(255, 255, 255, 0.3);
    display: inline-flex;
    align-items: center;
    backdrop-filter: blur(10px);
}

.btn-primary-modern:hover {
    background: white;
    color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    text-decoration: none;
}

/* Sección de Filtros */
.filters-section {
    background: white;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 2rem;
    border: 1px solid var(--border-color);
}

.filters-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.search-container {
    width: 100%;
}

.search-input-wrapper {
    position: relative;
    max-width: 500px;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted-text);
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 0.875rem 1rem 0.875rem 3rem;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-size: 1rem;
    transition: var(--transition);
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-clear {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--muted-text);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 50%;
    transition: var(--transition);
}

.search-clear:hover {
    background: var(--light-bg);
    color: var(--danger-color);
}

.filters-container {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
    min-width: 150px;
    transition: var(--transition);
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-filter, .btn-clear {
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
}

.btn-filter {
    background: var(--primary-gradient);
    color: white;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-clear {
    background: var(--light-bg);
    color: var(--muted-text);
    border: 1px solid var(--border-color);
}

.btn-clear:hover {
    background: #e9ecef;
    text-decoration: none;
}

/* Controles de Vista */
.view-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.view-toggle {
    display: flex;
    background: var(--light-bg);
    border-radius: 8px;
    padding: 0.25rem;
    border: 1px solid var(--border-color);
}

.view-btn {
    padding: 0.5rem 0.75rem;
    border: none;
    background: transparent;
    color: var(--muted-text);
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.875rem;
}

.view-btn.active,
.view-btn:hover {
    background: white;
    color: var(--primary-color);
    box-shadow: var(--shadow-sm);
}

.results-info {
    color: var(--muted-text);
    font-size: 0.875rem;
}

/* Grid de Clientes */
.clientes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Tarjetas de Cliente */
.cliente-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    transition: var(--transition);
    overflow: hidden;
    position: relative;
    animation: slideInUp 0.6s ease forwards;
    opacity: 0;
    transform: translateY(20px);
}

.cliente-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.cliente-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.estado-activo {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.estado-inactivo {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.cliente-avatar {
    text-align: center;
    padding: 2rem 1rem 1rem;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto;
    text-transform: uppercase;
    box-shadow: var(--shadow-md);
}

.cliente-info {
    padding: 0 1.5rem;
    text-align: center;
}

.cliente-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark-text);
    margin: 0 0 0.5rem;
    line-height: 1.3;
}

.cliente-document {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.document-type {
    background: var(--light-bg);
    color: var(--muted-text);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.document-number {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--dark-text);
}

.cliente-contact {
    padding: 0 1.5rem;
    margin-bottom: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.contact-item:last-child {
    border-bottom: none;
}

.contact-icon {
    width: 16px;
    color: var(--muted-text);
    flex-shrink: 0;
}

.contact-text {
    font-size: 0.875rem;
    color: var(--dark-text);
    line-height: 1.4;
}

.cliente-meta {
    padding: 0 1.5rem;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
}

.meta-label {
    color: var(--muted-text);
}

.meta-value {
    color: var(--dark-text);
    font-weight: 600;
}

.cliente-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: var(--light-bg);
    border-top: 1px solid var(--border-color);
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 0.875rem;
}

.view-btn {
    background: #e3f2fd;
    color: #1976d2;
}

.view-btn:hover {
    background: #1976d2;
    color: white;
    transform: scale(1.1);
}

.edit-btn {
    background: #fff3e0;
    color: #f57c00;
}

.edit-btn:hover {
    background: #f57c00;
    color: white;
    transform: scale(1.1);
    text-decoration: none;
}

.delete-btn {
    background: #ffebee;
    color: #d32f2f;
}

.delete-btn:hover {
    background: #d32f2f;
    color: white;
    transform: scale(1.1);
}

/* Estado Vacío */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
}

.empty-icon {
    font-size: 4rem;
    color: var(--muted-text);
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 0.5rem;
}

.empty-description {
    color: var(--muted-text);
    margin-bottom: 2rem;
}

/* Vista de Tabla */
.table-view {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.clientes-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.clientes-table thead th {
    background: var(--light-bg);
    color: var(--dark-text);
    font-weight: 600;
    padding: 1rem 0.75rem;
    text-align: left;
    border-bottom: 2px solid var(--border-color);
    white-space: nowrap;
}

.clientes-table tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid #f1f3f4;
}

.clientes-table tbody tr:hover {
    background: #f8f9fa;
}

.clientes-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table-id {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--muted-text);
}

.document-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.document-type-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    width: fit-content;
}

.client-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.client-name {
    font-weight: 600;
    color: var(--dark-text);
}

.client-contact-name {
    color: var(--muted-text);
    font-size: 0.8rem;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.contact-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
}

.contact-icon-small {
    width: 12px;
    color: var(--muted-text);
}

.clasificacion-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.date-info {
    color: var(--muted-text);
    font-size: 0.8rem;
}

.table-actions {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
}

.table-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 0.75rem;
}

.table-action-btn.view {
    background: #e3f2fd;
    color: #1976d2;
}

.table-action-btn.view:hover {
    background: #1976d2;
    color: white;
    text-decoration: none;
}

.table-action-btn.edit {
    background: #fff3e0;
    color: #f57c00;
}

.table-action-btn.edit:hover {
    background: #f57c00;
    color: white;
    text-decoration: none;
}

.table-action-btn.delete {
    background: #ffebee;
    color: #d32f2f;
}

.table-action-btn.delete:hover {
    background: #d32f2f;
    color: white;
}

/* Paginación */
.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.pagination-container .pagination {
    margin: 0;
}

.pagination-container .page-item {
    margin: 0 2px;
}

.pagination-container .page-link {
    background: rgba(255, 255, 255, 0.8);
    border: 2px solid #e5e7eb;
    color: #374151;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 45px;
    height: 45px;
}

.pagination-container .page-link:hover {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.pagination-container .page-item.active .page-link {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border-color: #3b82f6;
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.pagination-container .page-item.disabled .page-link {
    background: #f3f4f6;
    border-color: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
}

.pagination-container .page-item.disabled .page-link:hover {
    background: #f3f4f6;
    transform: none;
    box-shadow: none;
}

/* Paginación personalizada moderna */
.modern-pagination {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.pagination-wrapper {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-item {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 45px;
    height: 45px;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    color: #374151;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.pagination-item:hover:not(.disabled):not(.active) {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.pagination-item.active {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border-color: #3b82f6;
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    transform: scale(1.05);
}

.pagination-item.disabled {
    background: #f3f4f6;
    border-color: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
    opacity: 0.6;
}

.pagination-item.dots {
    background: transparent;
    border: none;
    color: #6b7280;
    cursor: default;
    font-weight: bold;
}

.pagination-info {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.pagination-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.9rem;
}

.pagination-info .font-medium {
    color: #374151;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination-wrapper {
        gap: 0.25rem;
    }
    
    .pagination-item {
        min-width: 40px;
        height: 40px;
        padding: 0.5rem;
        font-size: 0.9rem;
    }
}

/* Animaciones */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cliente-card:nth-child(1) { animation-delay: 0.1s; }
.cliente-card:nth-child(2) { animation-delay: 0.2s; }
.cliente-card:nth-child(3) { animation-delay: 0.3s; }
.cliente-card:nth-child(4) { animation-delay: 0.4s; }
.cliente-card:nth-child(5) { animation-delay: 0.5s; }
.cliente-card:nth-child(6) { animation-delay: 0.6s; }

/* Responsive Design */
@media (max-width: 1200px) {
    .clientes-grid {
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-stats {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .clientes-header {
        padding: 1.5rem;
    }
    
    .header-title {
        font-size: 1.5rem;
    }
    
    .header-stats {
        flex-direction: row;
        gap: 0.5rem;
    }
    
    .stat-card {
        padding: 0.75rem 0.5rem;
        min-width: 70px;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .filters-section {
        padding: 1rem;
    }
    
    .filters-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-select {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .filter-actions {
        justify-content: center;
    }
    
    .view-controls {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    
    .clientes-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .cliente-card {
        margin: 0 -1rem;
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
    
    .cliente-card:first-child {
        border-top-left-radius: var(--border-radius);
        border-top-right-radius: var(--border-radius);
        border-top: 1px solid var(--border-color);
        margin-top: 0;
    }
    
    .cliente-card:last-child {
        border-bottom-left-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
        border-bottom: 1px solid var(--border-color);
    }
}

@media (max-width: 480px) {
    .search-input-wrapper {
        max-width: none;
    }
    
    .header-left {
        flex-direction: column;
        text-align: center;
    }
    
    .header-icon {
        align-self: center;
    }
    
    .cliente-name {
        font-size: 1.1rem;
    }
    
    .cliente-actions {
        padding: 0.75rem;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
    }
}

/* Modal Personalizado */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: var(--shadow-lg);
}

.modal-header {
    background: var(--light-bg);
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.modal-title {
    color: var(--dark-text);
    font-weight: 600;
}
</style>

<!-- JavaScript para funcionalidad interactiva -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle entre vista de tarjetas y tabla
    const viewButtons = document.querySelectorAll('.view-btn');
    const cardsView = document.getElementById('cardsView');
    const tableView = document.getElementById('tableView');
    
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Actualizar botones activos
            viewButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Mostrar/ocultar vistas
            if (view === 'cards') {
                cardsView.style.display = 'block';
                tableView.style.display = 'none';
            } else {
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
            }
            
            // Guardar preferencia en localStorage
            localStorage.setItem('clientesViewMode', view);
        });
    });
    
    // Restaurar vista preferida
    const savedView = localStorage.getItem('clientesViewMode');
    if (savedView) {
        document.querySelector(`[data-view="${savedView}"]`).click();
    }
    
    // Función para limpiar búsqueda
    window.clearSearch = function() {
        const searchInput = document.querySelector('.search-input');
        const clearButton = document.querySelector('.search-clear');
        
        searchInput.value = '';
        clearButton.style.display = 'none';
        
        // Auto-submit para limpiar resultados
        searchInput.closest('form').submit();
    };
    
    // Mostrar/ocultar botón de limpiar búsqueda
    const searchInput = document.querySelector('.search-input');
    const clearButton = document.querySelector('.search-clear');
    
    searchInput.addEventListener('input', function() {
        clearButton.style.display = this.value ? 'block' : 'none';
    });
    
    // Auto-submit en búsqueda (con debounce)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 2 || this.value.length === 0) {
                this.closest('form').submit();
            }
        }, 500);
    });
    
    // Función para confirmar eliminación
    window.confirmDelete = function(clienteId, clienteNombre) {
        document.getElementById('clienteName').textContent = clienteNombre;
        document.getElementById('deleteForm').action = `/clientes/${clienteId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    };
    
    // Animaciones de entrada para las tarjetas
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observar tarjetas de cliente
    document.querySelectorAll('.cliente-card').forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        observer.observe(card);
    });
    
    // Tooltip para información adicional
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Inicializar tooltips si Bootstrap está disponible
    if (typeof bootstrap !== 'undefined') {
        initTooltips();
    }
    
    // Búsqueda en tiempo real para la tabla (opcional)
    function initTableSearch() {
        const tableRows = document.querySelectorAll('.clientes-table tbody tr');
        
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const shouldShow = text.includes(query);
                row.style.display = shouldShow ? '' : 'none';
            });
        });
    }
    
    // Efectos hover mejorados para tarjetas
    document.querySelectorAll('.cliente-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-5px)';
        });
    });
    
    // Smooth scroll para paginación
    document.querySelectorAll('.pagination a, .pagination-item').forEach(link => {
        if (!link.classList.contains('disabled') && !link.classList.contains('active')) {
            link.addEventListener('click', function() {
                // Agregar efecto de carga
                this.style.opacity = '0.6';
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                setTimeout(() => {
                    document.querySelector('.clientes-header').scrollIntoView({
                        behavior: 'smooth'
                    });
                }, 100);
            });
        }
    });
    
    // Loading state para filtros
    const filterForm = document.querySelector('.filters-form');
    filterForm.addEventListener('submit', function() {
        const submitBtn = this.querySelector('.btn-filter');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Filtrando...';
        submitBtn.disabled = true;
        
        // Restaurar después de un tiempo (para casos donde no hay redirección)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K para enfocar búsqueda
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Escape para limpiar búsqueda
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            clearSearch();
        }
    });
});
</script>

@endsection