<?php
/*
 * >>> PASO 1 de 4: descomposición del problema (solo TODOs, sin código PHP) <<<
 *
 * TAREA-03 MEDIA: Carrito de la compra
 *  - el catálogo y el carrito son objetos; la página solo llama a sus métodos
 *  - el carrito viaja entre peticiones SERIALIZADO en un campo oculto (todavía sin sesiones)
 */
// TODO: incluir las clases (require_once 'clases/Carrito.php', que incluye a las demás)

// TODO: 1) comprobar estado: recuperar el carrito del campo oculto con Carrito::desdeTexto()
//          (si no es válido: aviso y carrito vacío)

// TODO: 2) ¿qué botón se ha pulsado? (dentro de un try ... catch de InvalidArgumentException)
//          - anadir (value = código; la cantidad viene en cantidad[código])
//          - mas / menos (cambiarCantidad +1 / -1)
//          - quitar
//          - vaciar

// Función auxiliar TODO: e(string $texto): string        -> htmlspecialchars
// Función auxiliar TODO: euros(float $importe): string   -> "1.234,50 €"
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda online</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h1>🛒 Tienda online</h1>

    <!-- TODO: mensajes de error y de información -->

    <!-- TODO: UN formulario POST con:
               - un botón invisible al principio (Intro no debe añadir el primer producto)
               - el catálogo agrupado por categorías (Catalogo::porCategoria()): nombre, detalles, precio con
                 IVA, tipo de IVA, aviso si no es Enviable, caja de cantidad y botón Añadir
               - el carrito: líneas con botones − / + / ✕, unidades (count), desglose de IVA, envío y total
               - botón Vaciar
               - guardar el estado en una variable hidden: el carrito serializado ($carrito->serializar()) -->
</body>

</html>
