/* ============================================================
   app.js — Utilidades compartidas de la interfaz
   ============================================================ */

(function () {
  'use strict';

  // ==================== Traducciones de DataTables ====================
  var idiomaEspanol = {
    emptyTable: 'Sin datos disponibles',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty: 'Mostrando 0 a 0 de 0 registros',
    infoFiltered: '(filtrado de _MAX_ registros totales)',
    lengthMenu: 'Mostrar _MENU_ registros',
    loadingRecords: 'Cargando...',
    processing: 'Procesando...',
    search: 'Buscar:',
    zeroRecords: 'No se encontraron coincidencias',
    paginate: {
      first: 'Primero',
      last: 'Último',
      next: 'Siguiente',
      previous: 'Anterior'
    },
    aria: {
      sortAscending: ': activar para ordenar ascendente',
      sortDescending: ': activar para ordenar descendente'
    }
  };

  // ==================== Inicialización de tablas ====================
  /**
   * Inicializa una tabla con DataTables usando la traducción local.
   * @param {string} selector Selector de la tabla.
   * @param {object} opciones Opciones extra para DataTables.
   */
  window.iniciarTabla = function (selector, opciones) {
    if (typeof window.DataTable === 'undefined' || !document.querySelector(selector)) {
      return null;
    }

    var configuracion = Object.assign({
      paging: true,
      searching: true,
      ordering: true,
      info: true,
      lengthChange: true,
      pageLength: 10,
      language: idiomaEspanol
    }, opciones || {});

    return new window.DataTable(selector, configuracion);
  };

  // ==================== Notificaciones de interfaz ====================
  /**
   * Muestra un mensaje en un contenedor con la clase .notif.
   * @param {string|HTMLElement} contenedor Id del contenedor o elemento.
   * @param {string} mensaje Texto a mostrar.
   * @param {string} tipo "ok" o "error".
   */
  window.notificar = function (contenedor, mensaje, tipo) {
    var elemento = typeof contenedor === 'string'
      ? document.getElementById(contenedor)
      : contenedor;

    if (!elemento) {
      window.alert(mensaje);
      return;
    }

    elemento.textContent = mensaje;
    elemento.className = 'notif ' + (tipo === 'error' ? 'error' : 'ok');

    window.clearTimeout(elemento._ocultarNotificacion);
    elemento._ocultarNotificacion = window.setTimeout(function () {
      elemento.className = 'notif';
      elemento.textContent = '';
    }, 6000);
  };

  // ==================== Envío AJAX de formularios ====================
  /**
   * Envía un formulario por fetch y muestra la respuesta del servidor.
   * @param {HTMLFormElement} formulario Formulario a enviar.
   * @param {string} url Endpoint que procesa el formulario.
   * @param {object} opciones notificacion, onSuccess, onError, reset.
   */
  window.enviarFormulario = function (formulario, url, opciones) {
    if (!formulario) {
      return;
    }

    var configuracion = Object.assign({
      notificacion: null,
      onSuccess: null,
      onError: null,
      reset: false
    }, opciones || {});

    formulario.addEventListener('submit', async function (evento) {
      evento.preventDefault();

      var boton = formulario.querySelector('button[type="submit"]');
      var textoOriginal = boton ? boton.textContent : '';
      if (boton) {
        boton.disabled = true;
        boton.textContent = 'Guardando...';
      }

      try {
        var respuesta = await fetch(url, {
          method: 'POST',
          body: new FormData(formulario),
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        var texto = await respuesta.text();
        var datos;

        try {
          datos = JSON.parse(texto);
        } catch (error) {
          throw new Error('El servidor devolvió una respuesta inválida.');
        }

        if (!datos.ok) {
          window.notificar(configuracion.notificacion, datos.mensaje || 'No se pudo completar la operación.', 'error');
          if (configuracion.onError) {
            configuracion.onError(datos, formulario);
          }
          return;
        }

        window.notificar(configuracion.notificacion, datos.mensaje || 'Operación realizada correctamente.', 'ok');

        if (configuracion.onSuccess) {
          configuracion.onSuccess(datos, formulario);
        } else if (configuracion.reset) {
          formulario.reset();
        }
      } catch (error) {
        window.notificar(configuracion.notificacion, error.message || 'No se pudo conectar con el servidor.', 'error');
        if (configuracion.onError) {
          configuracion.onError(error, formulario);
        }
      } finally {
        if (boton) {
          boton.disabled = false;
          boton.textContent = textoOriginal;
        }
      }
    });
  };
})();
