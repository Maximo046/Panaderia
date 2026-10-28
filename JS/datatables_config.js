(function () {
  'use strict';

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
})();