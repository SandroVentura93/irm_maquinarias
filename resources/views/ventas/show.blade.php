@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-eye"></i> Detalles de la Venta</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">#{{ $venta->id_venta }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-right">
            <div class="alert alert-info mb-0">
                <small><i class="fas fa-exchange-alt"></i> TC: S/ {{ number_format($tipoCambio, 2) }}/USD</small>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <!-- Información del Comprobante -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice"></i>
                        Venta #{{ $venta->id_venta }}
                    </h5>
                    <div class="d-flex align-items-center">
                        @if($venta->id_tipo_comprobante == 4)
                            <span class="badge badge-primary badge-lg">COTIZACIÓN</span>
                        @elseif($venta->id_tipo_comprobante == 1)
                            <span class="badge badge-success badge-lg">FACTURA</span>
                        @elseif($venta->id_tipo_comprobante == 2)
                            <span class="badge badge-info badge-lg">BOLETA</span>
                        @elseif($venta->id_tipo_comprobante == 3)
                            <span class="badge badge-danger badge-lg">NOTA DE CRÉDITO</span>
                        @endif
                        <span class="badge badge-{{ $venta->xml_estado === 'ANULADO' ? 'danger' : ($venta->xml_estado === 'ACEPTADO' ? 'success' : ($venta->xml_estado === 'RECHAZADO' ? 'warning' : 'info')) }} badge-lg ml-2">
                            {{ $venta->xml_estado }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2"><i class="fas fa-info-circle"></i> INFORMACIÓN GENERAL</h6>
                            
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Comprobante:</label>
                                <div class="h5">
                                    <span class="badge badge-secondary badge-lg">{{ $venta->serie }}-{{ str_pad($venta->numero, 8, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Fecha y Hora:</label>
                                <div class="h6">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i:s') }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Cliente:</label>
                                <div class="h6">
                                    <strong>{{ $venta->cliente->razon_social ?: $venta->cliente->nombre }}</strong>
                                    <br><small class="text-muted">{{ $venta->cliente->documento }}</small>
                                </div>
                            </div>

                            @if($venta->vendedor)
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Vendedor:</label>
                                <div class="h6">{{ $venta->vendedor->nombre }}</div>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Moneda:</label>
                                <div class="h6">
                                    <span class="badge badge-{{ $venta->moneda == 'USD' ? 'success' : 'primary' }}">
                                        {{ $venta->moneda == 'USD' ? 'Dólar Americano' : 'Sol Peruano' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-success border-bottom pb-2"><i class="fas fa-dollar-sign"></i> RESUMEN FINANCIERO</h6>
                            
                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Subtotal:</label>
                                <div class="h5 text-info mb-1">S/ {{ number_format($venta->subtotal, 2) }}</div>
                                <small class="text-muted">≈ ${{ number_format($venta->subtotal / $tipoCambio, 2) }} USD</small>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">IGV (18%):</label>
                                <div class="h5 text-warning mb-1">S/ {{ number_format($venta->igv, 2) }}</div>
                                <small class="text-muted">≈ ${{ number_format($venta->igv / $tipoCambio, 2) }} USD</small>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-muted">Total:</label>
                                <div class="h3 text-success mb-1">S/ {{ number_format($venta->total, 2) }}</div>
                                <small class="text-muted">≈ ${{ number_format($venta->total / $tipoCambio, 2) }} USD</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles de la Venta -->
            @if($venta->detalles && count($venta->detalles) > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Detalle de Productos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-right">Precio Unit.</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($venta->detalles as $detalle)
                                <tr>
                                    <td>
                                        <strong>{{ $detalle->producto->descripcion ?? $detalle->descripcion }}</strong>
                                        @if($detalle->producto && $detalle->producto->codigo)
                                            <br><small class="text-muted">Código: {{ $detalle->producto->codigo }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $detalle->cantidad }}</span>
                                    </td>
                                    <td class="text-right">
                                        <strong>S/ {{ number_format($detalle->precio, 2) }}</strong>
                                        <br><small class="text-muted">${{ number_format($detalle->precio / $tipoCambio, 2) }}</small>
                                    </td>
                                    <td class="text-right">
                                        <strong class="text-success">S/ {{ number_format($detalle->cantidad * $detalle->precio, 2) }}</strong>
                                        <br><small class="text-muted">${{ number_format(($detalle->cantidad * $detalle->precio) / $tipoCambio, 2) }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if($venta->xml_estado === 'ANULADO')
            <div class="card mt-3 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Información de Anulación</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-weight-bold text-muted">Fecha de Anulación:</label>
                            <div class="h6">{{ \Carbon\Carbon::parse($venta->fecha_anulacion)->format('d/m/Y H:i:s') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold text-muted">Motivo:</label>
                            <div class="h6">{{ $venta->motivo_anulacion }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Estado del Comprobante -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-clipboard-check"></i> Estado del Comprobante</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="display-4 {{ $venta->xml_estado === 'ANULADO' ? 'text-danger' : ($venta->xml_estado === 'ACEPTADO' ? 'text-success' : 'text-info') }}">
                            <i class="fas fa-{{ $venta->xml_estado === 'ANULADO' ? 'times-circle' : ($venta->xml_estado === 'ACEPTADO' ? 'check-circle' : 'clock') }}"></i>
                        </div>
                        <h5 class="{{ $venta->xml_estado === 'ANULADO' ? 'text-danger' : ($venta->xml_estado === 'ACEPTADO' ? 'text-success' : 'text-info') }}">
                            {{ $venta->xml_estado }}
                        </h5>
                    </div>
                    
                    @if($venta->xml_estado === 'PENDIENTE')
                        <div class="alert alert-warning">
                            <small><i class="fas fa-clock"></i> Comprobante pendiente de procesamiento</small>
                        </div>
                    @elseif($venta->xml_estado === 'ACEPTADO')
                        <div class="alert alert-success">
                            <small><i class="fas fa-check-circle"></i> Comprobante aceptado por SUNAT</small>
                        </div>
                    @elseif($venta->xml_estado === 'ANULADO')
                        <div class="alert alert-danger">
                            <small><i class="fas fa-times-circle"></i> Comprobante anulado</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Acciones -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-tools"></i> Acciones</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                    
                    @if($venta->xml_estado !== 'ANULADO')
                        <a href="{{ route('ventas.pdf', $venta) }}" class="btn btn-success btn-block" target="_blank">
                            <i class="fas fa-file-pdf"></i> Descargar PDF
                        </a>
                    @endif
                    
                    @if($venta->xml_estado === 'PENDIENTE')
                        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Editar Venta
                        </a>
                    @endif
                    
                    @if(in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO', 'ACEPTADO']))
                        <hr>
                        <a href="{{ route('ventas.confirm-cancel', $venta) }}" class="btn btn-danger btn-block">
                            <i class="fas fa-times"></i> Anular Venta
                        </a>
                    @endif
                </div>
            </div>

            <!-- Conversión de Cotización -->
            @if($venta->id_tipo_comprobante == 4 && $venta->xml_estado === 'PENDIENTE')
            <div class="card mt-3 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-exchange-alt"></i> Convertir Cotización</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Convierte esta cotización en un comprobante fiscal válido.
                    </p>
                    <a href="{{ route('ventas.convertir-factura', $venta) }}" class="btn btn-success btn-block">
                        <i class="fas fa-file-invoice"></i> Convertir a Factura
                    </a>
                    <a href="{{ route('ventas.convertir-boleta', $venta) }}" class="btn btn-info btn-block">
                        <i class="fas fa-receipt"></i> Convertir a Boleta
                    </a>
                </div>
            </div>
            @endif

            <!-- Información del Sistema -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info"></i> Información del Sistema</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block">
                        <strong>Creada:</strong> {{ $venta->created_at ? $venta->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </small>
                    @if($venta->updated_at && $venta->updated_at != $venta->created_at)
                    <small class="text-muted d-block">
                        <strong>Modificada:</strong> {{ $venta->updated_at->format('d/m/Y H:i') }}
                    </small>
                    @endif
                    @if($venta->hash)
                    <small class="text-muted d-block">
                        <strong>Hash:</strong> {{ substr($venta->hash, 0, 20) }}...
                    </small>
                    @endif
                </div>
            </div>

            <!-- Resumen Rápido -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-calculator"></i> Resumen Rápido</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-info">{{ count($venta->detalles ?? []) }}</h6>
                            <small class="text-muted">Productos</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success">{{ array_sum(array_column($venta->detalles->toArray() ?? [], 'cantidad')) }}</h6>
                            <small class="text-muted">Items Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota sobre precios -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-info">
                <small>
                    <i class="fas fa-info-circle"></i>
                    Los precios en dólares son calculados con el tipo de cambio actual: S/ {{ number_format($tipoCambio, 2) }} por USD.
                    Los valores pueden variar según el tipo de cambio del momento.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection