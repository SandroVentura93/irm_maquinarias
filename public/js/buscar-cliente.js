console.log('🔧 DIAGNÓSTICO: Archivo buscar-cliente.js cargado');

function buscarCliente() {
    alert('🎯 DIAGNÓSTICO: Función buscarCliente ejecutada');
    console.log('🔍 Iniciando diagnóstico de búsqueda...');
    
    const documento = document.getElementById('buscar_documento').value.trim();
    
    if (!documento) {
        alert('❌ Por favor ingrese un documento');
        return;
    }
    
    if (documento.length < 8 || documento.length > 11) {
        alert('❌ El documento debe tener entre 8 y 11 dígitos');
        return;
    }
    
    console.log('📋 Documento válido:', documento);
    alert('📋 Documento válido: ' + documento + '. Realizando búsqueda...');
    
    // Probar la API
    const apiUrl = '/irm_maquinarias/api/clientes/buscar/' + documento;
    console.log('🌐 URL API:', apiUrl);
    
    fetch(apiUrl)
        .then(response => {
            console.log('📡 Status:', response.status);
            
            if (response.status === 404) {
                alert('❌ Cliente no encontrado');
                return null;
            }
            
            if (!response.ok) {
                throw new Error('Error HTTP: ' + response.status);
            }
            
            return response.json();
        })
        .then(data => {
            if (data) {
                console.log('✅ Datos recibidos:', data);
                alert('✅ Cliente encontrado: ' + (data.nombre || data.razon_social || 'Sin nombre'));
                
                // Llenar campos
                if (data.nombre || data.razon_social) {
                    document.getElementById('cliente_nombre').value = data.nombre || data.razon_social;
                }
                if (data.direccion) {
                    document.getElementById('cliente_direccion').value = data.direccion;
                }
                if (data.telefono) {
                    document.getElementById('cliente_telefono').value = data.telefono;
                }
                if (data.correo) {
                    document.getElementById('cliente_correo').value = data.correo;
                }
                if (data.id) {
                    const selectCliente = document.getElementById('id_cliente');
                    if (selectCliente) {
                        selectCliente.value = data.id;
                    }
                }
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            alert('❌ Error: ' + error.message);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Configurando eventos...');
    
    const boton = document.getElementById('btnBuscarCliente');
    const input = document.getElementById('buscar_documento');
    
    if (boton) {
        console.log('✅ Botón encontrado');
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('👆 Click en botón detectado');
            buscarCliente();
        });
    } else {
        console.error('❌ Botón NO encontrado');
        alert('❌ ERROR: Botón de búsqueda no encontrado');
    }
    
    if (input) {
        console.log('✅ Input encontrado');
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarCliente();
            }
        });
    } else {
        console.error('❌ Input NO encontrado');
        alert('❌ ERROR: Campo de documento no encontrado');
    }
    
    console.log('🎯 Configuración completa');
});