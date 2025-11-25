@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header Moderno -->
    <div class="proveedores-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="header-info">
                    <h1 class="header-title">Gestión de Proveedores</h1>
                    <p class="header-subtitle">Administra tu red de proveedores</p>
                </div>
            </div>
            <div class="header-stats">
                <div class="stat-card">
                    <div class="stat-value">{{ method_exists($proveedores, 'total') ? $proveedores->total() : count($proveedores) }}</div>
                    <div class="stat-label">Total Proveedores</div>
                </div>
                <div class="stat-card">
                    @php
                        $totalActivos = \App\Models\Proveedor::where('activo', true)->count();
                    @endphp
                    <div class="stat-value">{{ $totalActivos }}</div>
                    <div class="stat-label">Activos</div>
                </div>
            </div>
            <div class="header-actions">
                <div class="view-toggle">
                    <button class="view-btn active" data-view="grid" title="Vista de Tarjetas">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="view-btn" data-view="list" title="Vista de Lista">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
                <a href="{{ route('proveedores.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus me-2"></i>
                    Nuevo Proveedor
                </a>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Vista de Tarjetas -->
    <div class="proveedores-grid" id="grid-view">
        @forelse($proveedores as $proveedor)
        <div class="proveedor-card">
            <div class="proveedor-card-header">
                <div class="proveedor-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="proveedor-status">
                    @if($proveedor->activo ?? true)
                        <span class="badge-success"><i class="fas fa-check-circle"></i> Activo</span>
                    @else
                        <span class="badge-inactive"><i class="fas fa-times-circle"></i> Inactivo</span>
                    @endif
                </div>
            </div>
            
            <div class="proveedor-card-body">
                <h3 class="proveedor-nombre">{{ $proveedor->razon_social }}</h3>
                <div class="proveedor-info">
                    <div class="info-row">
                        <i class="fas fa-id-card"></i>
                        <span>{{ $proveedor->tipo_documento }}: {{ $proveedor->numero_documento }}</span>
                    </div>
                    @if($proveedor->contacto)
                    <div class="info-row">
                        <i class="fas fa-user"></i>
                        <span>{{ $proveedor->contacto }}</span>
                    </div>
                    @endif
                    @if($proveedor->telefono)
                    <div class="info-row">
                        <i class="fas fa-phone"></i>
                        <span>{{ $proveedor->telefono }}</span>
                    </div>
                    @endif
                    @if($proveedor->correo)
                    <div class="info-row">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $proveedor->correo }}</span>
                    </div>
                    @endif
                </div>
                <div class="proveedor-meta">
                    <span class="proveedor-id"><i class="fas fa-hashtag"></i> ID: {{ $proveedor->id_proveedor }}</span>
                </div>
            </div>
            
            <div class="proveedor-card-footer">
                <a href="{{ route('proveedores.show', $proveedor->id_proveedor) }}" class="btn-view" title="Ver Detalles">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}" class="btn-edit" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('proveedores.destroy', $proveedor->id_proveedor) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este proveedor?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-truck"></i>
            <h3>No hay proveedores registrados</h3>
            <p>Comienza agregando tu primer proveedor</p>
            <a href="{{ route('proveedores.create') }}" class="btn-primary-modern">
                <i class="fas fa-plus me-2"></i> Crear Primer Proveedor
            </a>
        </div>
        @endforelse
    </div>

    <!-- Vista de Lista -->
    <div class="proveedores-list" id="list-view" style="display: none;">
        @if(count($proveedores) > 0)
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Razón Social</th>
                        <th>Documento</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proveedores as $proveedor)
                    <tr>
                        <td><span class="badge-id">#{{ $proveedor->id_proveedor }}</span></td>
                        <td><strong>{{ $proveedor->razon_social }}</strong></td>
                        <td>
                            <div class="documento-info">
                                <span class="tipo-doc">{{ $proveedor->tipo_documento }}</span>
                                <span class="num-doc">{{ $proveedor->numero_documento }}</span>
                            </div>
                        </td>
                        <td>{{ $proveedor->contacto ?? '-' }}</td>
                        <td>{{ $proveedor->telefono ?? '-' }}</td>
                        <td>{{ $proveedor->correo ?? '-' }}</td>
                        <td>
                            @if($proveedor->activo ?? true)
                                <span class="badge-success-sm"><i class="fas fa-check-circle"></i> Activo</span>
                            @else
                                <span class="badge-inactive-sm"><i class="fas fa-times-circle"></i> Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <a href="{{ route('proveedores.show', $proveedor->id_proveedor) }}" class="btn-action btn-action-view" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}" class="btn-action btn-action-edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('proveedores.destroy', $proveedor->id_proveedor) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este proveedor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-truck"></i>
            <h3>No hay proveedores registrados</h3>
            <p>Comienza agregando tu primer proveedor</p>
            <a href="{{ route('proveedores.create') }}" class="btn-primary-modern">
                <i class="fas fa-plus me-2"></i> Crear Primer Proveedor
            </a>
        </div>
        @endif
    </div>

    <!-- Paginación -->
    @if(method_exists($proveedores, 'links'))
    <div class="pagination-container">
        {{ $proveedores->links() }}
    </div>
    @endif
</div>

<style>
    /* Header Moderno */
    .proveedores-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(240, 147, 251, 0.3);
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
    }

    .header-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: white;
        backdrop-filter: blur(10px);
    }

    .header-info {
        color: white;
    }

    .header-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    .header-subtitle {
        margin: 5px 0 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .header-stats {
        display: flex;
        gap: 15px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 15px 25px;
        text-align: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .view-toggle {
        display: flex;
        background: white;
        border-radius: 10px;
        padding: 4px;
        gap: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .view-btn {
        padding: 10px 16px;
        border: none;
        background: transparent;
        color: #f5576c;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 16px;
    }

    .view-btn:hover {
        background: #fff5f7;
    }

    .view-btn.active {
        background: #f5576c;
        color: white;
    }

    .btn-primary-modern {
        background: white;
        color: #f5576c;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #f5576c;
    }

    /* Alert Moderno */
    .modern-alert {
        border: none;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
    }

    /* Grid de Proveedores */
    .proveedores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    /* Tarjeta de Proveedor */
    .proveedor-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .proveedor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .proveedor-card-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .proveedor-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        backdrop-filter: blur(10px);
    }

    .proveedor-status .badge-success {
        background: rgba(40, 167, 69, 0.9);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .proveedor-status .badge-inactive {
        background: rgba(220, 53, 69, 0.9);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .proveedor-card-body {
        padding: 20px;
    }

    .proveedor-nombre {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
        margin: 0 0 15px 0;
    }

    .proveedor-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 15px;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #4a5568;
    }

    .info-row i {
        color: #f5576c;
        width: 16px;
        text-align: center;
    }

    .proveedor-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
    }

    .proveedor-id {
        color: #a0aec0;
        font-size: 12px;
        font-weight: 600;
    }

    .proveedor-card-footer {
        padding: 15px 20px;
        background: #f7fafc;
        display: flex;
        gap: 10px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-view {
        flex: 1;
        background: #48bb78;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: none;
        cursor: pointer;
    }

    .btn-view:hover {
        background: #38a169;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
    }

    .btn-edit {
        flex: 1;
        background: #4299e1;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-edit:hover {
        background: #3182ce;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
    }

    .btn-delete {
        flex: 1;
        background: #fc8181;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-delete:hover {
        background: #f56565;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }

    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-state i {
        font-size: 64px;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 24px;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 25px;
    }

    /* Vista de Lista - Tabla Moderna */
    .table-responsive {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .table-modern thead th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background: #fff5f7;
    }

    .table-modern tbody tr:last-child {
        border-bottom: none;
    }

    .table-modern tbody td {
        padding: 16px 20px;
        color: #2d3748;
        font-size: 14px;
    }

    .badge-id {
        background: #ffe6f0;
        color: #f5576c;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    .documento-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .tipo-doc {
        font-size: 11px;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
    }

    .num-doc {
        font-size: 13px;
        color: #2d3748;
        font-weight: 600;
    }

    .badge-success-sm {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-inactive-sm {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-action-view {
        background: #48bb78;
        color: white;
        text-decoration: none;
    }

    .btn-action-view:hover {
        background: #38a169;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
    }

    .btn-action-edit {
        background: #4299e1;
        color: white;
        text-decoration: none;
    }

    .btn-action-edit:hover {
        background: #3182ce;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
    }

    .btn-action-delete {
        background: #fc8181;
        color: white;
    }

    .btn-action-delete:hover {
        background: #f56565;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
    }

    /* Paginación */
    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-left {
            flex-direction: column;
        }

        .header-stats {
            width: 100%;
            justify-content: center;
        }

        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .view-toggle {
            margin-bottom: 10px;
            justify-content: center;
        }

        .proveedores-grid {
            grid-template-columns: 1fr;
        }

        .table-modern {
            font-size: 12px;
        }

        .table-modern thead th,
        .table-modern tbody td {
            padding: 12px 10px;
        }
    }
</style>

<script>
    // Toggle entre vista de tarjetas y lista
    document.addEventListener('DOMContentLoaded', function() {
        const viewBtns = document.querySelectorAll('.view-btn');
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                
                // Remover clase active de todos los botones
                viewBtns.forEach(b => b.classList.remove('active'));
                
                // Agregar clase active al botón clickeado
                this.classList.add('active');
                
                // Mostrar/ocultar vistas
                if (view === 'grid') {
                    gridView.style.display = 'grid';
                    listView.style.display = 'none';
                } else {
                    gridView.style.display = 'none';
                    listView.style.display = 'block';
                }
                
                // Guardar preferencia en localStorage
                localStorage.setItem('proveedoresView', view);
            });
        });

        // Restaurar preferencia guardada
        const savedView = localStorage.getItem('proveedoresView') || 'grid';
        const savedBtn = document.querySelector(`.view-btn[data-view="${savedView}"]`);
        
        if (savedBtn) {
            savedBtn.click();
        }
    });
</script>
@endsection