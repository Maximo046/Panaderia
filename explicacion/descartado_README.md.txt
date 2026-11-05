ARCHIVO: descartado/README.md
PROPOSITO: inventariar contenido historico que ya no integra el sistema actual.
TIPO: documento Markdown para personas; no ejecutable.

Indica que ventas.php fue reemplazado por salidas.php, registrar_venta.php por PHP/registrar_salida.php y tiquet_venta.php ya no se usa. Tambien explica que imagenes y respaldos SQL se conservan como referencia.
La tabla Markdown contiene nombres de archivo y motivos; `#` introduce titulo, `|` separa columnas y backticks resaltan rutas.
El directorio incluye .htaccess para denegar acceso web. No importar dumps historicos en la base activa sin revisar su esquema y disponer de respaldo: pueden contener tablas antiguas de ventas y detalle_venta incompatibles con la estructura actual.
