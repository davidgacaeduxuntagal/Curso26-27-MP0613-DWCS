<?php
/*
 * e05-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: interface, implements, interfaces como tipo, Countable, Stringable,
 *                JsonSerializable, herencia de interfaces, trait, use, insteadof / as
 *  (repasa los ejemplos de ../Ejercicios OOP/e05-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Una interfaz para los envíos
//  Define la interfaz Transportista con los métodos coste(float $pesoKg): float y nombre():
//  string, e impleméntala en Correos (3,50 € + 1,20 € por kg) y Mensajeria (6,90 € fijos hasta
//  5 kg y 0,80 € por cada kg que pase de 5). Escribe la función masBarato(float $peso, array
//  $transportistas): string que devuelva el nombre del más barato.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
interface Transportista
{
    public function coste(float $pesoKg): float;

    public function nombre(): string;
}
class Correos implements Transportista
{
    public function coste(float $pesoKg): float
    {
        return round(3.50 + 1.20 * $pesoKg, 2);
    }

    public function nombre(): string
    {
        return 'Correos';
    }
}
class Mensajeria implements Transportista
{
    public function coste(float $pesoKg): float
    {
        return round(6.90 + 0.80 * max(0, $pesoKg - 5), 2);
    }

    public function nombre(): string
    {
        return 'Mensajería urgente';
    }
}
function masBarato(float $peso, array $transportistas): string
{
    usort($transportistas, fn(Transportista $a, Transportista $b) => $a->coste($peso) <=> $b->coste($peso));
    return $transportistas[0]->nombre();
}
$opciones = [new Correos(), new Mensajeria()];
ver('1 kg', masBarato(1, $opciones));     // "Correos" (4.70 frente a 6.90)
ver('8 kg', masBarato(8, $opciones));     // "Mensajería urgente" (9.30 frente a 13.10)

// =============================================================================================
// PROBLEMA 2. Una playlist con interfaces de PHP
//  Escribe la clase Playlist (nombre y canciones: array de ['titulo' => ..., 'segundos' => ...])
//  que implemente Countable (número de canciones), Stringable ("Nombre (N canciones, MM:SS)") y
//  JsonSerializable (nombre, canciones y duración total en segundos).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Playlist implements Countable, Stringable, JsonSerializable
{
    private array $canciones = [];

    public function __construct(private string $nombre)
    {
    }

    public function anadir(string $titulo, int $segundos): static
    {
        $this->canciones[] = ['titulo' => $titulo, 'segundos' => $segundos];
        return $this;
    }

    public function duracion(): int
    {
        return array_sum(array_column($this->canciones, 'segundos'));
    }

    public function count(): int
    {
        return count($this->canciones);
    }

    public function __toString(): string
    {
        return sprintf('%s (%d canciones, %02d:%02d)', $this->nombre, count($this), intdiv($this->duracion(), 60), $this->duracion() % 60);
    }

    public function jsonSerialize(): array
    {
        return ['nombre' => $this->nombre, 'canciones' => $this->canciones, 'duracion' => $this->duracion()];
    }
}
$lista = (new Playlist('Folk galego'))->anadir('Muiñeira de Chantada', 185)->anadir('Alalá', 240)->anadir('Pandeirada', 199);
ver('count', count($lista));         // 3
ver('texto', (string) $lista);       // "Folk galego (3 canciones, 10:24)"
ver('json', json_encode($lista, JSON_UNESCAPED_UNICODE));

// =============================================================================================
// PROBLEMA 3. Un trait para "dar me gusta"
//  Escribe el trait ConMeGusta (array de usuarios que han dado "me gusta"; métodos
//  darMeGusta(string $usuario): void, quitarMeGusta(string): void y meGusta(): int, sin
//  repetir usuarios) y úsalo en dos clases sin relación: Foto (url) y Comentario (texto).
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
trait ConMeGusta
{
    private array $meGusta = [];

    public function darMeGusta(string $usuario): void
    {
        $this->meGusta[$usuario] = true;          // la clave evita repetidos
    }

    public function quitarMeGusta(string $usuario): void
    {
        unset($this->meGusta[$usuario]);
    }

    public function meGusta(): int
    {
        return count($this->meGusta);
    }
}
class Foto
{
    use ConMeGusta;

    public function __construct(public string $url)
    {
    }
}
class Comentario
{
    use ConMeGusta;

    public function __construct(public string $texto)
    {
    }
}
$foto = new Foto('praia-de-riazor.jpg');
$foto->darMeGusta('ana');
$foto->darMeGusta('brais');
$foto->darMeGusta('ana');                         // repetido: no cuenta
$comentario = new Comentario('¡Qué bonita!');
$comentario->darMeGusta('carmen');
$comentario->quitarMeGusta('carmen');
ver('me gusta', [$foto->meGusta(), $comentario->meGusta()]);   // [2, 0]

// =============================================================================================
// PROBLEMA 4. Conflicto entre traits
//  Los traits Saludo y Despedida tienen los dos un método mensaje(). Escribe la clase Bot que
//  use los dos, de forma que mensaje() sea el de Saludo y el de Despedida se llame adios().
// =============================================================================================
titulo('Problema 4');
trait Saludo
{
    public function mensaje(): string
    {
        return 'Ola! En que che podo axudar?';
    }
}
trait Despedida
{
    public function mensaje(): string
    {
        return 'Ata logo!';
    }
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Bot
{
    use Saludo, Despedida {
        Saludo::mensaje insteadof Despedida;
        Despedida::mensaje as adios;
    }
}
$bot = new Bot();
ver('mensaje', $bot->mensaje());    // "Ola! En que che podo axudar?"
ver('adios', $bot->adios());        // "Ata logo!"

// =============================================================================================
// PROBLEMA 5. ¿Interfaz, clase abstracta o trait?
//  Para cada caso, ¿qué usarías y por qué? Responde en un comentario.
//   a) Todos los métodos de pago deben tener cobrar() y reembolsar(), pero cada uno los hace de
//      una forma completamente distinta.
//   b) Coche, Moto y Camión comparten matrícula, marca y el cálculo del impuesto de circulación,
//      y no tiene sentido crear un "Vehículo" a secas.
//   c) Usuarios, productos y pedidos (que no se parecen en nada) necesitan el mismo código para
//      guardar sus fechas de creación y modificación.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
// a) Una INTERFAZ (MetodoPago): define el contrato sin código; cada clase lo implementa a su
//    manera y una función puede recibir "cualquier MetodoPago".
// b) Una CLASE ABSTRACTA Vehiculo: tiene propiedades y código común (impuesto), no se puede
//    instanciar, y Coche, Moto y Camion heredan de ella.
// c) Un TRAIT (ConFechas): comparte CÓDIGO entre clases sin relación de herencia. Si además
//    hiciera falta usarlo como tipo, se añadiría una interfaz (ConFechasInterface).
ver('respuesta', 'a) interfaz, b) clase abstracta, c) trait');
