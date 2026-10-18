<?php
/*
 * utilidades.php
 *  Funciones auxiliares para los ejemplos de Programación Orientada a Objetos. Funcionan tanto
 *  desde la terminal / depurador de VSC (CLI) como desde el navegador (Apache).
 *
 *  CONSEJO para depurar: pon un punto de ruptura en la primera línea del ejemplo y avanza con
 *  "Step Over" (F10); usa "Step Into" (F11) para entrar en los métodos de las clases. En el panel
 *  VARIABLES despliega los objetos: verás su clase, sus propiedades y su visibilidad.
 */

/**
 * Muestra una etiqueta y un valor:
 *  - los strings, tal cual entre comillas dobles,
 *  - los objetos con su clase y cada propiedad con su visibilidad, por ejemplo:
 *        Producto {
 *          nombre => 'iPad Air'
 *          precio:protected => 699.0
 *          coste:Producto:private => 450.0
 *        }
 *    (las propiedades tipadas que todavía no tienen valor no aparecen),
 *  - el resto (números, true/false/null, arrays...) como en var_export.
 */
function ver(string $etiqueta, mixed $valor): void
{
    $texto = $etiqueta . ' => ' . (is_string($valor) ? '"' . $valor . '"' : volcar($valor));
    if (PHP_SAPI === 'cli') {
        echo $texto, PHP_EOL;
    } else {
        echo '<pre>', htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), '</pre>';
    }
}

/** Representación en texto de cualquier valor (recursiva para arrays y objetos). */
function volcar(mixed $valor, int $nivel = 0, ?SplObjectStorage $vistos = null): string
{
    $vistos ??= new SplObjectStorage();
    $sangria = str_repeat('  ', $nivel + 1);
    $cierre = str_repeat('  ', $nivel);
    if ($valor instanceof UnitEnum) {
        return 'enum ' . get_class($valor) . '::' . $valor->name;
    }
    if ($valor instanceof Closure) {
        return 'Closure';
    }
    if (is_resource($valor)) {
        return 'resource(' . get_resource_id($valor) . ') de tipo ' . get_resource_type($valor);
    }
    if (is_object($valor)) {
        if ($vistos->contains($valor)) {
            return get_class($valor) . ' { *ya mostrado* }';
        }
        $vistos->attach($valor);
        $lineas = [];
        foreach ((array) $valor as $clave => $dato) {
            // (array) marca las protegidas con "\0*\0nombre" y las privadas con "\0Clase\0nombre"
            $partes = explode("\0", (string) $clave);
            $nombre = match (count($partes)) {
                3 => $partes[1] === '*' ? $partes[2] . ':protected' : $partes[2] . ':' . $partes[1] . ':private',
                default => (string) $clave,
            };
            $lineas[] = $sangria . $nombre . ' => ' . volcar($dato, $nivel + 1, $vistos);
        }
        return get_class($valor) . ($lineas === [] ? ' { }' : " {\n" . implode("\n", $lineas) . "\n$cierre}");
    }
    if (is_array($valor)) {
        if ($valor === []) {
            return '[]';
        }
        $lineas = [];
        foreach ($valor as $clave => $dato) {
            $lineas[] = $sangria . var_export($clave, true) . ' => ' . volcar($dato, $nivel + 1, $vistos);
        }
        return "[\n" . implode(",\n", $lineas) . "\n$cierre]";
    }
    return var_export($valor, true);
}

/**
 * Ejecuta un trozo de código que DEBE fallar y muestra el error o la excepción que produce, sin
 * detener el ejemplo. Así se pueden ver los errores típicos (acceder a algo private, instanciar
 * una clase abstracta...) paso a paso. Los avisos (Warning, Deprecated...) también se muestran:
 * mientras se ejecuta el código se convierten en excepciones ErrorException.
 */
function verError(string $etiqueta, callable $codigo): void
{
    set_error_handler(function (int $nivel, string $mensaje): bool {
        throw new ErrorException($mensaje, 0, $nivel);
    });
    try {
        $codigo();
        ver($etiqueta, '(no ha fallado)');
    } catch (Throwable $e) {
        ver($etiqueta, get_class($e) . ': ' . $e->getMessage());
    } finally {
        restore_error_handler();
    }
}

/**
 * Muestra un título de sección.
 */
function titulo(string $texto): void
{
    if (PHP_SAPI === 'cli') {
        echo PHP_EOL, '===== ', $texto, ' =====', PHP_EOL;
    } else {
        echo '<h3>', htmlspecialchars($texto), '</h3>';
    }
}

/**
 * Salto de línea adecuado para la salida (en el navegador, <br>).
 */
function nl(): string
{
    return PHP_SAPI === 'cli' ? PHP_EOL : '<br>';
}
