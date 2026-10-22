<?php
/*
 * >>> PASO 2 de 4: productos, catálogo, carrito básico y estado serializado en un campo oculto <<<
 *
 * TAREA-03 MEDIA: Carrito de la compra
 *  - el catálogo y el carrito son objetos; la página solo llama a sus métodos
 *  - el carrito viaja entre peticiones SERIALIZADO en un campo oculto (todavía sin sesiones)
 */
require_once 'clases/Carrito.php';

$errores  = [];
$mensajes = [];

// ---------------------------------------------------------------------- 1) Recuperar el carrito
$carrito = Carrito::desdeTexto($_POST['carrito'] ?? '');
if ($carrito === null) {
    $errores[] = "El carrito recibido no es válido (¿se ha modificado la página?). Se ha vaciado.";
    $carrito   = new Carrito();
}

// ---------------------------------------------------------------------- 2) Acciones
// Cada botón envía su acción en su nombre y el código del producto en su valor
try {
    if (isset($_POST['anadir'])) {
        $codigo   = (string) $_POST['anadir'];
        $cantidad = filter_var($_POST['cantidad'][$codigo] ?? 1, FILTER_VALIDATE_INT);
        if ($cantidad === false || $cantidad < 1) {
            $errores[] = "La cantidad debe ser un número entero positivo.";
        } else {
            $carrito->anadir($codigo, $cantidad);
            $mensajes[] = "Añadido: {$cantidad} x " . Catalogo::buscar($codigo)->getNombre();
        }
    } elseif (isset($_POST['mas'])) {
        $carrito->cambiarCantidad((string) $_POST['mas'], +1);
    } elseif (isset($_POST['menos'])) {
        $carrito->cambiarCantidad((string) $_POST['menos'], -1);
    } elseif (isset($_POST['quitar'])) {
        $carrito->quitar((string) $_POST['quitar']);
    } elseif (isset($_POST['vaciar'])) {
        $carrito->vaciar();
        $mensajes[] = "Se ha vaciado el carrito.";
    }
} catch (InvalidArgumentException $e) {
    // Las validaciones de las clases (cantidad máxima, código inexistente...) llegan aquí
    $errores[] = $e->getMessage();
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function euros(float $importe): string
{
    return Producto::formato($importe) . " €";
}
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

    <?php foreach ($errores as $error): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endforeach; ?>
    <?php foreach ($mensajes as $mensaje): ?>
        <p class="mensaje"><?= e($mensaje) ?></p>
    <?php endforeach; ?>

    <form method="post">
        <!-- Al pulsar Intro en una caja de cantidad, el navegador "pulsa" el PRIMER botón del formulario.
             Este botón invisible va el primero para que Intro no añada por error el primer producto. -->
        <button type="submit" name="refrescar" value="1" class="oculto" tabindex="-1" aria-hidden="true">Actualizar</button>
        <div class="columnas">
            <!-- ===== Catálogo ===== -->
            <section class="catalogo">
                <h2>Catálogo</h2>
                <?php foreach (Catalogo::porCategoria() as $categoria => $productos): ?>
                    <h3><?= e($categoria) ?></h3>
                    <table>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td>
                                    <strong><?= e($producto->getNombre()) ?></strong><br>
                                    <small><?= e($producto->detalles()) ?></small>
                                    <?php if (!($producto instanceof Enviable)): ?>
                                        <small class="aviso">· solo recogida en tienda</small>
                                    <?php endif; ?>
                                </td>
                                <td class="precio"><?= euros($producto->precioConIva()) ?><br>
                                    <small>IVA <?= $producto->tipoIva() * 100 ?> %</small></td>
                                <td class="anadir">
                                    <input type="number" min="1" max="<?= LineaCarrito::MAX_UNIDADES ?>" value="1"
                                           name="cantidad[<?= $producto->getCodigo() ?>]">
                                    <button type="submit" name="anadir" value="<?= $producto->getCodigo() ?>">Añadir</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endforeach; ?>
            </section>

            <!-- ===== Carrito ===== -->
            <section class="carrito">
                <h2>Tu carrito (<?= count($carrito) ?> unidades)</h2>
                <?php if ($carrito->estaVacio()): ?>
                    <p>El carrito está vacío.</p>
                <?php else: ?>
                    <table>
                        <?php foreach ($carrito->getLineas() as $codigo => $linea): ?>
                            <tr>
                                <td><?= e($linea->getProducto()->getNombre()) ?><br>
                                    <small><?= euros($linea->getProducto()->precioConIva()) ?> / ud.</small></td>
                                <td class="cantidad">
                                    <button type="submit" name="menos" value="<?= $codigo ?>">−</button>
                                    <?= $linea->getCantidad() ?>
                                    <button type="submit" name="mas" value="<?= $codigo ?>">+</button>
                                </td>
                                <td class="precio"><?= euros($linea->subtotal()) ?></td>
                                <td><button type="submit" name="quitar" value="<?= $codigo ?>" title="Quitar">✕</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                    <table class="resumen">
                        <!-- TODO (paso 3): desglose de IVA por tipo -->
                        <tr>
                            <td>Productos (IVA incluido)</td>
                            <td class="precio"><?= euros($carrito->totalProductos()) ?></td>
                        </tr>
                        <!-- TODO (paso 3): gastos de envío (o "Solo recogida en tienda", o "Gratis") -->
                        <tr class="total">
                            <td>TOTAL</td>
                            <td class="precio"><?= euros($carrito->total()) ?></td>
                        </tr>
                    </table>
                    <!-- TODO (paso 3): aviso de cuánto falta para el envío gratis -->
                    <button type="submit" name="vaciar" value="1" class="vaciar">Vaciar carrito</button>
                <?php endif; ?>
            </section>
        </div>

        <!-- ESTADO: el carrito serializado (solo códigos y cantidades, gracias a __sleep) -->
        <input type="hidden" name="carrito" value="<?= e($carrito->serializar()) ?>">
    </form>
</body>

</html>
