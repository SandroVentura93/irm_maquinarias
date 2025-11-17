@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center p-5">
                    <!-- Icono de Error -->
                    <div class="mb-4">
                        @if($code == 404)
                            <i class="fas fa-search text-warning" style="font-size: 4rem;"></i>
                        @elseif($code == 500)
                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                        @else
                            <i class="fas fa-exclamation-circle text-info" style="font-size: 4rem;"></i>
                        @endif
                    </div>
                    
                    <!-- Código de Error -->
                    <h1 class="display-4 text-muted mb-3">{{ $code }}</h1>
                    
                    <!-- Mensaje -->
                    <h4 class="mb-4">
                        @if($code == 404)
                            Página No Encontrada
                        @elseif($code == 500)
                            Error del Servidor
                        @else
                            Error {{ $code }}
                        @endif
                    </h4>
                    
                    <p class="text-muted mb-4">{{ $message }}</p>
                    
                    <!-- Sugerencias -->
                    <div class="alert alert-light border-0 mb-4">
                        <h6 class="mb-2">💡 Sugerencias:</h6>
                        <ul class="list-unstyled text-start mb-0">
                            @if($code == 404)
                                <li>• Verifique la URL ingresada</li>
                                <li>• Use la navegación del menú</li>
                                <li>• Contacte al administrador si el problema persiste</li>
                            @elseif($code == 500)
                                <li>• Intente recargar la página</li>
                                <li>• Verifique su conexión a internet</li>
                                <li>• Contacte al soporte técnico</li>
                            @else
                                <li>• Intente la operación nuevamente</li>
                                <li>• Verifique los datos ingresados</li>
                                <li>• Contacte al administrador</li>
                            @endif
                        </ul>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="d-flex gap-3 justify-content-center">
                        <button onclick="window.history.back()" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Regresar
                        </button>
                        
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>
                            Ir al Dashboard
                        </a>
                        
                        <button onclick="window.location.reload()" class="btn btn-outline-success">
                            <i class="fas fa-sync-alt me-2"></i>
                            Recargar
                        </button>
                    </div>
                    
                    <!-- Información adicional -->
                    @if(app()->environment('local'))
                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                Timestamp: {{ now()->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.btn {
    border-radius: 25px;
    padding: 10px 20px;
}

.alert {
    border-radius: 10px;
}

@media (max-width: 576px) {
    .d-flex.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.gap-3 .btn {
        margin-bottom: 10px;
    }
}
</style>
@endsection