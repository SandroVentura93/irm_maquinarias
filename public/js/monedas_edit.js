document.addEventListener('DOMContentLoaded', function() {
  const nombreInput = document.getElementById('nombre');
  const simboloInput = document.getElementById('simbolo');
  const codigoInput = document.getElementById('codigo_iso');

  const previewNombre = document.getElementById('preview-nombre');
  const previewSimbolo = document.getElementById('preview-simbolo');
  const previewCodigo = document.getElementById('preview-codigo');

  if (nombreInput && previewNombre) {
    nombreInput.addEventListener('input', function() {
      previewNombre.textContent = this.value || previewNombre.dataset.default || '';
    });
    // set default for fallback
    previewNombre.dataset.default = previewNombre.textContent;
  }

  if (simboloInput && previewSimbolo) {
    simboloInput.addEventListener('input', function() {
      previewSimbolo.textContent = this.value || previewSimbolo.dataset.default || '';
    });
    previewSimbolo.dataset.default = previewSimbolo.textContent;
  }

  if (codigoInput && previewCodigo) {
    const enforceUpperAndLength = function(value) {
      const upper = (value || '').toUpperCase();
      return upper.length > 3 ? upper.substring(0, 3) : upper;
    };

    codigoInput.addEventListener('input', function() {
      const fixed = enforceUpperAndLength(this.value);
      this.value = fixed;
      previewCodigo.textContent = fixed || previewCodigo.dataset.default || '';
    });

    // Initialize defaults
    const fixedInit = enforceUpperAndLength(codigoInput.value);
    codigoInput.value = fixedInit;
    previewCodigo.dataset.default = previewCodigo.textContent;
  }
});
