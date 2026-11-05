function enviarFormularioAjax(formulario, url, opciones) {
  if (!formulario) return;

  var config = opciones || {};

  formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    var peticion = new XMLHttpRequest();
    peticion.open('POST', url, true);
    peticion.onreadystatechange = function () {
      if (peticion.readyState == 4) {
        var respuesta;

        try {
          respuesta = JSON.parse(peticion.responseText);
        } catch (error) {
          if (config.mostrarAlerta !== false) alert('No se pudo completar la operación.');
          if (typeof config.onError === 'function') config.onError(error, formulario);
          return;
        }

        if (config.mostrarAlerta !== false) alert(respuesta.mensaje);

        if (respuesta.ok) {
          if (config.reset) formulario.reset();
          if (config.recargar) window.location.reload();
          if (typeof config.onSuccess === 'function') config.onSuccess(respuesta, formulario);
        } else if (typeof config.onError === 'function') {
          config.onError(respuesta, formulario);
        }
      }
    };
    peticion.send(new FormData(formulario));
  });
}
