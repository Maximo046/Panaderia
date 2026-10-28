function enviarFormularioAjax(formulario, url, opciones) {
  if (!formulario) return;
  var config = opciones || {};
  formulario.addEventListener('submit', function(evento) {
    evento.preventDefault();
    var boton = formulario.querySelector('button[type="submit"]');
    var textoOriginal = boton ? boton.textContent : '';
    if (boton) {
      boton.disabled = true;
      boton.textContent = 'Guardando...';
    }
    var peticion = new XMLHttpRequest();
    peticion.open('POST', url, true);
    peticion.onreadystatechange = function() {
      if (peticion.readyState == 4) {
        if (boton) {
          boton.disabled = false;
          boton.textContent = textoOriginal;
        }
        try {
          var respuesta = JSON.parse(peticion.responseText);
          if (config.mostrarAlerta !== false) {
            alert(respuesta.mensaje);
          }
          if (respuesta.ok) {
            if (config.reset) formulario.reset();
            if (config.recargar) window.location.reload();
            if (typeof config.onSuccess === 'function') {
              config.onSuccess(respuesta, formulario);
            }
          } else {
            if (typeof config.onError === 'function') {
              config.onError(respuesta, formulario);
            }
          }
        } catch(e) {
          if (config.mostrarAlerta !== false) {
            alert('No se pudo completar la operación.');
          }
          if (typeof config.onError === 'function') {
            config.onError(e, formulario);
          }
        }
      }
    };
    peticion.send(new FormData(formulario));
  });
}