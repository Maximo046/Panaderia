<?php
// ============================================================
// ayuda.php — Guía de uso, FAQ y formulario de contacto
// ============================================================

$pagina_actual = 'ayuda';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Ayuda e Información</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.3.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="JS/AJAX.js"></script>
</head>
<body>

  <?php include 'sidebar.php'; ?>

  <main class="main-content">
    <!-- ==================== Encabezado de ayuda ==================== -->
    <header class="page-header">
      <h1 class="page-title">Ayuda e <em>Información</em></h1>
      <p class="page-subtitle">Guía de uso, preguntas frecuentes y contacto con el administrador.</p>
    </header>

    <!-- ==================== Guía rápida y preguntas frecuentes ==================== -->
    <div class="row g-4 mb-4">
      <div class="col-lg-6">
        <section class="card h-100" aria-label="Guía rápida de uso">
          <div class="card-header"><h2>Guía Rápida de Uso</h2></div>
          <div class="card-body">
            <div class="list-group list-group-flush guia-list">
              <?php
              $items_guia = [
                  ['Stock', 'Consultá en tiempo real el stock disponible de insumos y productos. También muestra alertas cuando algún artículo está por debajo del mínimo establecido.'],
                  ['Insumos', 'Registrá la compra de materias primas indicando el insumo, cantidad, precio, proveedor y método de pago. También podés dar de alta nuevos insumos con su unidad de medida.'],
                  ['Producción', 'Registrá la producción de cada producto ingresando la cantidad fabricada. El sistema descuenta automáticamente los insumos usados según la receta y suma el stock de productos disponibles.'],
                  ['Productos', 'Administrá los productos de la panadería y sus recetas. Podés agregar nuevos productos, asignarles una unidad de medida y definir qué insumos y en qué cantidad requiere cada uno.'],
                  ['Salidas', 'Registrá la salida de productos del depósito indicando cantidad y motivo (venta mostrador, consumo interno, merma, rotura, donación u otro). El sistema descuenta el stock automáticamente, sin registrar precios ni facturación.'],
                  ['Reportes', 'Visualizá el historial completo de consumos de insumos, ingresos de materias primas, producción y salidas de productos. Podés filtrar por mes para analizar el movimiento en un período específico y descargar el reporte en PDF.'],
                  ['Ayuda', 'Encontrás esta guía de uso, las preguntas frecuentes y un formulario de contacto para comunicarte con el administrador del sistema ante cualquier duda o inconveniente.'],
              ];
              foreach ($items_guia as [$titulo_seccion, $descripcion_seccion]):
              ?>
                <div class="list-group-item guia-item">
                  <div class="guia-info">
                    <span class="guia-titulo"><?= $titulo_seccion ?></span>
                    <span class="guia-desc"><?= $descripcion_seccion ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
      </div>

      <div class="col-lg-6">
        <section class="card h-100" aria-label="Preguntas frecuentes">
          <div class="card-header"><h2>Preguntas Frecuentes</h2></div>
          <div class="card-body">
            <div class="accordion" id="acordeonFaq">
              <?php
              $preguntas_frecuentes = [
                  ['¿Cómo registro una entrada de insumo?', 'Ingresá a <strong>Insumos</strong>, seleccioná el insumo, completá la cantidad y fecha, y luego enviá el formulario.'],
                  ['¿Cómo produzo un producto?', 'En <strong>Producción</strong>, elegí el producto, ingresá la cantidad a producir y el sistema ajustará el stock de insumos automáticamente.'],
                  ['¿Cómo registro la salida de un producto?', 'Usá la pestaña <strong>Salidas</strong>, ingresá la cantidad de cada producto que sale del depósito, elegí el motivo y confirmá. El sistema descuenta el stock automáticamente.'],
                  ['¿Cómo veo los reportes por mes?', 'Ingresá a <strong>Reportes</strong>, seleccioná el mes deseado y verás los ingresos de insumos, consumos, producción y salidas de productos. Desde allí también podés descargar el PDF.'],
              ];
              foreach ($preguntas_frecuentes as $indice => [$pregunta, $respuesta]):
              ?>
                <div class="accordion-item">
                  <h3 class="accordion-header" id="faq-encabezado-<?= $indice ?>">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq-respuesta-<?= $indice ?>"
                            aria-expanded="false" aria-controls="faq-respuesta-<?= $indice ?>">
                      <?= $pregunta ?>
                    </button>
                  </h3>
                  <div id="faq-respuesta-<?= $indice ?>" class="accordion-collapse collapse"
                       aria-labelledby="faq-encabezado-<?= $indice ?>" data-bs-parent="#acordeonFaq">
                    <div class="accordion-body"><?= $respuesta ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
      </div>
    </div>

    <!-- ==================== Formulario e información de contacto ==================== -->
    <section class="card contacto-card" aria-label="Formulario de contacto">
      <div class="card-header"><h2>Contacto</h2></div>
      <div class="card-body">
        <div id="notif-contacto" class="notif d-none" role="status" aria-live="polite"></div>

        <form id="form-contacto" action="PHP/formulario_ayuda.php" method="post">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nombre" class="form-label">Ingrese su nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="col-md-6">
              <label for="correo" class="form-label">Ingrese su correo</label>
              <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="col-md-6">
              <label for="asunto" class="form-label">Seleccione el asunto</label>
              <select class="form-select" name="asunto" id="asunto" required>
                <option value="">Seleccione una categoría</option>
                <?php
                $lista_asuntos = mysqli_query($conexion, "SELECT idasunto, nombre FROM asunto");
                while ($asunto = mysqli_fetch_assoc($lista_asuntos)):
                ?>
                  <option value="<?= (int) $asunto['idasunto'] ?>"><?= htmlspecialchars($asunto['nombre']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="mensaje" class="form-label">Mensaje</label>
              <textarea class="form-control" id="mensaje" name="mensaje" rows="4"></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
          </div>
        </form>

        <div class="contacto-info mt-4">
          <div class="row g-3">
            <div class="col-md-4 info-bloque">
              <span class="info-icono"></span>
              <div><strong>Dirección</strong><p>Escuela Normal N°10<br>Misiones, Argentina</p></div>
            </div>
            <div class="col-md-4 info-bloque">
              <span class="info-icono"></span>
              <div><strong>Correo</strong><p>pedrozomaximo76@gmail.com</p></div>
            </div>
            <div class="col-md-4 info-bloque">
              <span class="info-icono"></span>
              <div><strong>Horario de atención</strong><p>Lunes a viernes<br>18:30 a 23:00 hs</p></div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      enviarFormularioAjax(document.getElementById('form-contacto'), 'PHP/formulario_ayuda.php', {
        reset: true
      });
    });
  </script>
  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
