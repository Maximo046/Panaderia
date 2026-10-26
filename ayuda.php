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
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Ayuda e Información</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="JS/app.js" defer></script>
</head>
<body>
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon"><img src="CSS/Imagenes/Logo.png" alt="Logo panadería"></div>
      <div class="brand-text">
        <span class="brand-name">Panadería Normal 10</span>
        <span class="brand-sub">Gestión de Panadería</span>
      </div>
    </div>
    <ul class="nav-links">
      <?php include 'sidebar.php'; ?>
    </ul>
    <div class="sidebar-footer">
      <span>Escuela Normal N°10 · 2026</span>
      <?php include 'admin_registro_link.php'; ?>
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </aside>

  <main class="main-content">
    <header class="page-header">
      <div>
        <h1 class="page-title">Ayuda e <em>Información</em></h1>
      </div>
    </header>

    <div class="help-grid">
      <section class="card guia-card" aria-label="Guía rápida de uso">
        <header class="card-header"><h2>Guía Rápida de Uso:</h2></header>
        <ul class="guia-list">
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
            <li class="guia-item">
              <div class="guia-info">
                <span class="guia-titulo"><?= $titulo_seccion ?></span>
                <span class="guia-desc"><?= $descripcion_seccion ?></span>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>

      <section class="card faq-card" aria-label="Preguntas frecuentes">
        <header class="card-header"><h2>Preguntas Frecuentes:</h2></header>
        <div class="faq-list">
          <?php
          $preguntas_frecuentes = [
              ['¿Cómo registro una entrada de insumo?', 'Ingresá a <strong>Insumos</strong>, seleccioná el insumo, completá la cantidad y fecha, y luego enviá el formulario.'],
              ['¿Cómo produzo un producto?', 'En <strong>Producción</strong>, elegí el producto, ingresá la cantidad a producir y el sistema ajustará el stock de insumos automáticamente.'],
              ['¿Cómo registro la salida de un producto?', 'Usá la pestaña <strong>Salidas</strong>, ingresá la cantidad de cada producto que sale del depósito, elegí el motivo y confirmá. El sistema descuenta el stock automáticamente.'],
              ['¿Cómo veo los reportes por mes?', 'Ingresá a <strong>Reportes</strong>, seleccioná el mes deseado y verás los ingresos de insumos, consumos, producción y salidas de productos. Desde allí también podés descargar el PDF.'],
          ];
          foreach ($preguntas_frecuentes as [$pregunta, $respuesta]):
          ?>
            <article class="faq-item">
              <details>
                <summary class="faq-pregunta"><?= $pregunta ?> <span class="faq-arrow">▸</span></summary>
                <div class="faq-respuesta"><?= $respuesta ?></div>
              </details>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    </div>

    <section class="card contacto-card" aria-label="Formulario de contacto">
      <header class="card-header"><h2>Contacto:</h2></header>

      <div id="notif-contacto" class="notif" role="status"></div>

      <form id="form-contacto" action="PHP/formulario_ayuda.php" method="post" class="contacto-form">
        <div class="form-group">
          <label for="nombre">Ingrese su nombre</label>
          <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
          <label for="correo">Ingrese su correo</label>
          <input type="email" id="correo" name="correo" required>
        </div>
        <div class="form-group">
          <label for="asunto">Seleccione el asunto</label>
          <div class="select-wrapper">
            <select name="asunto" id="asunto" required>
              <option value="">Seleccione una categoría</option>
              <?php
              $lista_asuntos = mysqli_query($conexion, "SELECT idasunto, nombre FROM asunto");
              while ($asunto = mysqli_fetch_assoc($lista_asuntos)):
              ?>
                <option value="<?= (int) $asunto['idasunto'] ?>"><?= htmlspecialchars($asunto['nombre']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="mensaje">Mensaje</label>
          <textarea id="mensaje" name="mensaje" rows="4"></textarea>
        </div>
        <div class="form-full">
          <button type="submit" class="btn-primary">Enviar</button>
        </div>
      </form>

      <div class="contacto-info">
        <div class="info-bloque">
          <span class="info-icono"></span>
          <div><strong>Dirección</strong><p>Escuela Normal N°10<br>Misiones, Argentina</p></div>
        </div>
        <div class="info-bloque">
          <span class="info-icono"></span>
          <div><strong>Correo</strong><p>pedrozomaximo76@gmail.com</p></div>
        </div>
        <div class="info-bloque">
          <span class="info-icono"></span>
          <div><strong>Horario de atención</strong><p>Lunes a viernes<br>18:30 a 23:00 hs</p></div>
        </div>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      window.enviarFormulario(
        document.getElementById('form-contacto'),
        'PHP/formulario_ayuda.php',
        { notificacion: 'notif-contacto', reset: true }
      );
    });
  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
