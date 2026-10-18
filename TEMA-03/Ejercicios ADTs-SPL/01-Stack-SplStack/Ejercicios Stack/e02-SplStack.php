<?php
/*
 * e02-SplStack.php
 *  PILA (Stack) con SplStack (2): APLICACIONES REALES
 *  Contenidos: deshacer / rehacer con dos pilas dentro de una clase propia (Editor), comprobar
 *              que las etiquetas HTML están bien anidadas, evaluar expresiones en notación
 *              polaca inversa (calculadora) y recorrer carpetas sin recursividad (pila explícita)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los bucles y ejecuta en modo depuración.
 *   2. En cada vuelta, mira con ver() o en el panel VARIABLES cómo crece y decrece la pila: es la
 *      mejor forma de entender por qué cada problema se resuelve con una pila.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Deshacer y rehacer con dos pilas
// ENLACE: https://www.php.net/manual/es/class.splstack.php
// ENUNCIADO EJEMPLO: un editor con Ctrl+Z (deshacer) y Ctrl+Y (rehacer).
// NOTA: cada cambio se apila en "deshacer". Al deshacer, se saca de "deshacer" y se apila en
//       "rehacer". Al hacer un cambio NUEVO, la pila de rehacer se vacía (ya no tiene sentido).
// ---------------------------------------------------------------------------------------------
titulo('Deshacer y rehacer');
final class Editor
{
    private string $texto = '';
    private SplStack $deshacer;
    private SplStack $rehacer;

    public function __construct()
    {
        $this->deshacer = new SplStack();
        $this->rehacer = new SplStack();
    }

    public function escribir(string $trozo): void
    {
        $this->deshacer->push($this->texto);     // guardamos el estado ANTERIOR
        $this->texto .= $trozo;
        $this->rehacer = new SplStack();         // un cambio nuevo invalida el "rehacer"
    }

    public function deshacer(): void
    {
        if (!$this->deshacer->isEmpty()) {
            $this->rehacer->push($this->texto);
            $this->texto = $this->deshacer->pop();
        }
    }

    public function rehacer(): void
    {
        if (!$this->rehacer->isEmpty()) {
            $this->deshacer->push($this->texto);
            $this->texto = $this->rehacer->pop();
        }
    }

    public function texto(): string
    {
        return $this->texto;
    }
}
$editor = new Editor();
$editor->escribir('Hola');
$editor->escribir(', mundo');
$editor->escribir('!');
ver('texto', $editor->texto());          // "Hola, mundo!"
$editor->deshacer();
$editor->deshacer();
ver('tras 2 deshacer', $editor->texto()); // "Hola"
$editor->rehacer();
ver('tras rehacer', $editor->texto());   // "Hola, mundo"
$editor->escribir(' cruel');
$editor->rehacer();                      // no hace nada: el cambio nuevo vació "rehacer"
ver('final', $editor->texto());          // "Hola, mundo cruel"

// ---------------------------------------------------------------------------------------------
// Etiquetas HTML bien anidadas
// ENLACE: https://www.php.net/manual/es/function.preg-match-all.php
// ENUNCIADO EJEMPLO: comprobar el HTML que escriben los usuarios en un comentario.
// NOTA: cada etiqueta de apertura se apila; cada cierre debe coincidir con la CIMA. Al final la
//       pila debe estar vacía. (Las etiquetas vacías como <br> o <img> no se apilan.)
// ---------------------------------------------------------------------------------------------
titulo('HTML bien anidado');
function htmlBienAnidado(string $html): bool|string
{
    $vacias = ['br', 'img', 'hr', 'input', 'meta', 'link'];
    $abiertas = new SplStack();
    preg_match_all('#<(/?)([a-z][a-z0-9]*)[^>]*>#i', $html, $etiquetas, PREG_SET_ORDER);
    foreach ($etiquetas as [, $barra, $nombre]) {
        $nombre = strtolower($nombre);
        if (in_array($nombre, $vacias, true)) {
            continue;
        }
        if ($barra === '') {
            $abiertas->push($nombre);                                    // apertura
        } elseif ($abiertas->isEmpty() || $abiertas->pop() !== $nombre) {
            return "cierre inesperado </$nombre>";
        }
    }
    return $abiertas->isEmpty() ? true : 'sin cerrar: <' . $abiertas->top() . '>';
}
ver('correcto', htmlBienAnidado('<p>Hola <b>mundo</b><br> <i>cursiva</i></p>'));   // true
ver('cruzado', htmlBienAnidado('<p><b>Hola</p></b>'));                          // "cierre inesperado </p>"
ver('sin cerrar', htmlBienAnidado('<div><p>Hola</p>'));                         // "sin cerrar: <div>"

// ---------------------------------------------------------------------------------------------
// Calculadora en notación polaca inversa (RPN)
// ENLACE: https://es.wikipedia.org/wiki/Notaci%C3%B3n_polaca_inversa
// ENUNCIADO EJEMPLO: evaluar "3 4 + 2 *" (que equivale a (3 + 4) * 2).
// NOTA: los números se apilan; al leer un operador se sacan los dos de arriba (¡en orden inverso!),
//       se operan y se apila el resultado. Así funcionan muchas calculadoras y la máquina virtual
//       que ejecuta PHP.
// ---------------------------------------------------------------------------------------------
titulo('Notación polaca inversa');
function evaluarRpn(string $expresion): float
{
    $pila = new SplStack();
    foreach (preg_split('/\s+/', trim($expresion)) as $token) {
        if (is_numeric($token)) {
            $pila->push((float) $token);
            continue;
        }
        if (count($pila) < 2) {
            throw new InvalidArgumentException("Faltan operandos para '$token'");
        }
        $b = $pila->pop();                   // el de arriba es el SEGUNDO operando
        $a = $pila->pop();
        $pila->push(match ($token) {
            '+' => $a + $b,
            '-' => $a - $b,
            '*' => $a * $b,
            '/' => $b == 0 ? throw new DivisionByZeroError('División entre 0') : $a / $b,
            default => throw new InvalidArgumentException("Operador desconocido: $token"),
        });
    }
    if (count($pila) !== 1) {
        throw new InvalidArgumentException('Expresión incompleta');
    }
    return $pila->pop();
}
ver('3 4 + 2 *', evaluarRpn('3 4 + 2 *'));           // 14.0
ver('10 2 8 * + 3 -', evaluarRpn('10 2 8 * + 3 -'));  // 23.0  (10 + 2*8 - 3)
ver('9 3 /', evaluarRpn('9 3 /'));                    // 3.0
verError('3 +', fn() => evaluarRpn('3 +'));           // InvalidArgumentException: Faltan operandos para '+'

// ---------------------------------------------------------------------------------------------
// Recorrer un árbol sin recursividad (pila explícita)
// ENLACE: https://www.php.net/manual/es/class.splstack.php
// ENUNCIADO EJEMPLO: listar todas las carpetas y ficheros de un "disco" (un array anidado) en
//                   profundidad, como haría scandir de forma recursiva.
// NOTA: la recursividad usa por debajo una pila (la pila de llamadas). Con una SplStack propia se
//       puede hacer lo mismo con un bucle while, sin riesgo de desbordar la pila de llamadas.
// ---------------------------------------------------------------------------------------------
titulo('Recorrido en profundidad');
$disco = [
    'documentos' => ['cv.pdf' => 120, 'facturas' => ['enero.pdf' => 80, 'febrero.pdf' => 75]],
    'fotos' => ['playa.jpg' => 2400],
    'notas.txt' => 2,
];
$pendientes = new SplStack();
foreach (array_reverse($disco, true) as $nombre => $elemento) {   // al revés: el primero queda en la cima
    $pendientes->push(['/' . $nombre, $elemento]);
}
$rutas = [];
$totalKb = 0;
while (!$pendientes->isEmpty()) {
    [$ruta, $elemento] = $pendientes->pop();                       // pon aquí un punto de ruptura
    if (is_array($elemento)) {                                     // carpeta: su contenido, a la pila
        $rutas[] = $ruta . '/';
        foreach (array_reverse($elemento, true) as $nombre => $hijo) {
            $pendientes->push([$ruta . '/' . $nombre, $hijo]);
        }
    } else {                                                       // fichero
        $rutas[] = "$ruta ($elemento KB)";
        $totalKb += $elemento;
    }
}
ver('rutas', $rutas);
// ['/documentos/', '/documentos/cv.pdf (120 KB)', '/documentos/facturas/', '/documentos/facturas/enero.pdf (80 KB)',
//  '/documentos/facturas/febrero.pdf (75 KB)', '/fotos/', '/fotos/playa.jpg (2400 KB)', '/notas.txt (2 KB)']
ver('total (KB)', $totalKb);     // 2677
