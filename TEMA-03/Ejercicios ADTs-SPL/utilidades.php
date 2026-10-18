<?php
/*
 * utilidades.php
 *  Funciones auxiliares para los ejemplos de tipos de datos abstractos (ADT) con la SPL.
 *  Funcionan tanto desde la terminal / depurador de VSC (CLI) como desde el navegador (Apache).
 *
 *  CONSEJO para depurar: pon un punto de ruptura en la primera línea del ejemplo y avanza con
 *  "Step Over" (F10). Las estructuras de la SPL (SplStack, SplHeap...) guardan sus datos
 *  INTERNAMENTE: en el panel VARIABLES de VS Code a veces solo se ven algunos campos internos
 *  (flags, dllist, heap...). Por eso ver() las muestra con su contenido en el orden en que se
 *  extraerían (sin modificarlas: trabaja sobre una copia).
 */

/**
 * Muestra una etiqueta y un valor:
 *  - los strings, tal cual entre comillas dobles,
 *  - las estructuras de la SPL, con su contenido en orden (ver volcar()),
 *  - los demás objetos, con su clase y la visibilidad de cada propiedad,
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

/** Representación en texto de cualquier valor (recursiva). Las estructuras SPL se copian antes de recorrerlas. */
function volcar(mixed $valor, int $nivel = 0): string
{
    $sangria = str_repeat('  ', $nivel + 1);
    $cierre = str_repeat('  ', $nivel);
    $lista = fn(array $elementos) => $elementos === [] ? '' : implode(', ', array_map(fn($e) => volcar($e, $nivel + 1), $elementos));

    if ($valor instanceof UnitEnum) {
        return 'enum ' . get_class($valor) . '::' . $valor->name;
    }
    if ($valor instanceof SplStack) {                       // antes que SplDoublyLinkedList (hereda de ella)
        return get_class($valor) . '(' . count($valor) . ') cima -> [' . $lista(iterator_to_array(clone $valor, false)) . '] <- fondo';
    }
    if ($valor instanceof SplQueue) {
        return get_class($valor) . '(' . count($valor) . ') frente -> [' . $lista(iterator_to_array(clone $valor, false)) . '] <- final';
    }
    if ($valor instanceof SplDoublyLinkedList) {
        $copia = clone $valor;
        $copia->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP);
        return get_class($valor) . '(' . count($valor) . ') [' . $lista(iterator_to_array($copia, false)) . ']';
    }
    if ($valor instanceof SplPriorityQueue) {
        $copia = clone $valor;
        $copia->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        $elementos = [];
        while (!$copia->isEmpty()) {
            $e = $copia->extract();
            $elementos[] = volcar($e['data'], $nivel + 1) . ' (prioridad ' . volcar($e['priority'], $nivel + 1) . ')';
        }
        return get_class($valor) . '(' . count($valor) . ') en orden de salida: [' . implode(', ', $elementos) . ']';
    }
    if ($valor instanceof SplHeap) {
        if ($valor->isCorrupted()) {
            return get_class($valor) . '(' . count($valor) . ') *CORRUPTO*';
        }
        $copia = clone $valor;
        $elementos = [];
        foreach ($copia as $e) {                             // recorrer un montículo lo vacía: por eso la copia
            $elementos[] = $e;
        }
        return get_class($valor) . '(' . count($valor) . ') en orden de salida: [' . $lista($elementos) . ']';
    }
    if ($valor instanceof SplFixedArray) {
        $lineas = [];
        foreach ($valor->toArray() as $i => $e) {
            $lineas[] = "$i => " . volcar($e, $nivel + 1);
        }
        return 'SplFixedArray(' . $valor->getSize() . ') [' . implode(', ', $lineas) . ']';
    }
    if ($valor instanceof SplObjectStorage) {
        $lineas = [];
        $copia = clone $valor;                               // no movemos el puntero del original
        foreach ($copia as $i => $objeto) {
            $dato = $copia->getInfo();
            $lineas[] = $sangria . volcar($objeto, $nivel + 1) . ($dato === null ? '' : ' => ' . volcar($dato, $nivel + 1));
        }
        return get_class($valor) . '(' . count($valor) . ')' . ($lineas === [] ? ' { }' : " {\n" . implode("\n", $lineas) . "\n$cierre}");
    }
    if ($valor instanceof Closure) {
        return 'Closure';
    }
    if ($valor instanceof DateTimeInterface) {
        return get_class($valor) . '(' . $valor->format('Y-m-d H:i') . ')';
    }
    if (is_object($valor)) {
        $partes = [];
        foreach ((array) $valor as $clave => $dato) {
            // (array) marca las protegidas con "\0*\0nombre" y las privadas con "\0Clase\0nombre"
            $trozos = explode("\0", (string) $clave);
            $nombre = count($trozos) === 3 ? $trozos[2] . ($trozos[1] === '*' ? ':protected' : ':private') : (string) $clave;
            $partes[] = $nombre . ': ' . volcar($dato, $nivel + 1);
        }
        return get_class($valor) . ' {' . implode(', ', $partes) . '}';
    }
    if (is_array($valor)) {
        if ($valor === []) {
            return '[]';
        }
        if (array_is_list($valor) && count(array_filter($valor, 'is_array')) === 0) {
            return '[' . $lista($valor) . ']';                // lista sencilla en una línea
        }
        $lineas = [];
        foreach ($valor as $clave => $dato) {
            $lineas[] = $sangria . var_export($clave, true) . ' => ' . volcar($dato, $nivel + 1);
        }
        return "[\n" . implode(",\n", $lineas) . "\n$cierre]";
    }
    return var_export($valor, true);
}

/**
 * Ejecuta un trozo de código que DEBE fallar y muestra el error o la excepción que produce, sin
 * detener el ejemplo (por ejemplo, sacar de una pila vacía). Los avisos se convierten en
 * excepciones ErrorException mientras se ejecuta el código.
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

/** Muestra un título de sección. */
function titulo(string $texto): void
{
    if (PHP_SAPI === 'cli') {
        echo PHP_EOL, '===== ', $texto, ' =====', PHP_EOL;
    } else {
        echo '<h3>', htmlspecialchars($texto), '</h3>';
    }
}

/** Salto de línea adecuado para la salida (en el navegador, <br>). */
function nl(): string
{
    return PHP_SAPI === 'cli' ? PHP_EOL : '<br>';
}
