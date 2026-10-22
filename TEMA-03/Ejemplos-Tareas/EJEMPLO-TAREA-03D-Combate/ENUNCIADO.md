# TAREA-03 (nivel AVANZADO): Combate por turnos

**CFGS Desarrollo de Aplicaciones Web — MP0613 Desarrollo web en entorno servidor — Tema 3 (POO en PHP)**

## Caso práctico

Para la semana cultural, el club de videojuegos del instituto quiere un juego de rol sencillo en la web: el jugador
forma un equipo de tres personajes y se enfrenta por turnos a una horda de enemigos. **Juan** ve la oportunidad de
que **Carlos** practique todo lo que se puede hacer con clases en PHP: herencia, clases abstractas, interfaces,
traits, métodos mágicos, clonación y serialización.

## Enunciado

Programa un combate por turnos entre el equipo del jugador y un equipo enemigo controlado por el ordenador.
No se usa `$_SESSION`: la partida se guarda **serializada y firmada** en un campo oculto.

### Parte 1: los personajes

- **R1. Clase abstracta `Personaje`**:
  - atributos **protegidos**: nombre (1 a 20 caracteres), vida, vida máxima, ataque, defensa y enfriamiento (turnos
    que faltan para poder repetir la habilidad especial). Se validan en el constructor;
  - **acceso de solo lectura** con métodos mágicos: `__get` permite leer `nombre`, `vida`, `vidaMax`, `ataque`,
    `defensa` y `enfriamiento` desde fuera (`$p->vida`), y `__set` lanza una `LogicException` si se intenta
    modificar cualquier atributo (`$p->vida = 9999`);
  - métodos abstractos: `clase()`, `icono()`, `nombreEspecial()`, `especialNecesitaObjetivo()`,
    `enfriamientoEspecial()` y `ejecutarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo)`;
  - `final recibirDanio(int $danio, bool $ignorarDefensa = false): int`: el daño se reduce con la defensa, es como
    mínimo 1 y nunca supera la vida que queda;
  - `curar()`, `estaVivo()`, `porcentajeVida()`, `atacar(Personaje $objetivo)` (ataque normal con una pequeña
    variación al azar) y `finTurno()`;
  - **método plantilla** `final usarEspecial(...)`: comprueba que se puede usar (`puedeUsarEspecial()`) y que hay
    objetivo si hace falta, llama a `ejecutarEspecial()` y pone el enfriamiento;
  - `__toString()`, un contador estático por clase y `numeroCreados()`;
  - `static crearAlAzar(?string $nombre = null): static`, escrito **una sola vez** con `new static(...)`, y
    `static aleatorio()`, que crea un personaje de una clase al azar.
- **R2. Maná: interfaz + trait.** La interfaz `UsaMana` declara `getMana()`, `getManaMax()`, `tieneMana()`,
  `gastarMana()` y `regenerarMana()`. El trait `ConMana` contiene los atributos y la implementación de esos
  métodos. Lo usan las dos clases con maná, que **no tienen un padre común propio**.
- **R3. Cuatro clases concretas** (constructor que recibe solo el nombre; estadísticas con ±10 % al azar):

  | Clase | Vida | Ataque | Defensa | Habilidad especial | Espera |
  |---|---:|---:|---:|---|---:|
  | `Guerrero` | 130 | 17 | 8 | *Golpe brutal*: doble de daño a un enemigo | 3 |
  | `Arquero` | 90 | 15 | 5 | *Disparo certero*: 1,5 × ataque a un enemigo **ignorando su defensa** | 2 |
  | `Mago` (UsaMana, 40 de maná) | 75 | 10 | 3 | *Bola de fuego*: ataque + 8 a **todos** los enemigos; cuesta 20 de maná | 1 |
  | `Sanador` (UsaMana, 50 de maná) | 85 | 9 | 4 | *Curación en grupo*: +25 de vida a **todos** los aliados; cuesta 20 de maná | 1 |

  Mago y Sanador redefinen `puedeUsarEspecial()` (comprueban también el maná) y `finTurno()` (regeneran 5 y 6 de
  maná respectivamente, llamando a `parent::finTurno()`).

### Parte 2: equipos y combate

- **R4. Clase `Equipo`**, que implementa `Countable`: de 1 a 4 personajes (validado), `vivos()` (conservando los
  índices), `estaDerrotado()`, `masDebil()`, `hayHeridos($porcentaje)`, `finTurno()`, `static aleatorio()` y un
  **`__clone()` profundo** que clona también a cada personaje.
- **R5. Clase `Combate`**: los dos equipos, la ronda, el personaje activo del jugador, el historial (las 12 últimas
  líneas) y el resultado. El método `jugar(string $accion, ?int $objetivo)` hace:
  1. actúa el personaje activo del jugador ('atacar' o 'especial');
  2. si quedan enemigos, actúa un enemigo (por orden). IA: usa su especial si puede (el sanador solo si algún
     aliado tiene menos del 70 % de vida) y, si no, ataca al personaje más débil del jugador;
  3. fin de turno de ambos equipos;
  4. pasa el turno al siguiente personaje vivo del jugador; al dar la vuelta empieza una nueva ronda.

  Las jugadas no válidas (acción desconocida, objetivo muerto o inexistente, especial no disponible, combate
  terminado) lanzan una excepción y **no cambian nada**.
- **R6. Deshacer.** Antes de cada jugada se guarda una copia del combate con `clone`. `deshacer()` devuelve esa
  copia. Con un `__clone()` profundo, la copia no debe compartir objetos con el original, ni guardar a su vez otra
  copia: solo se puede deshacer una jugada.

### Parte 3: guardar la partida

- **R7. Clase `Partida`** (solo métodos estáticos):
  - `guardar(Combate)`: `base64_encode(serialize($combate))` + `"."` + firma `hash_hmac('sha256', ...)` con una
    clave secreta;
  - `cargar(string)`: comprueba la firma con `hash_equals` y deserializa con `allowed_classes`. Devuelve `null` si
    la partida ha sido manipulada.

### Parte 4: la página

- **R8. Nueva partida:** formulario con el nombre (opcional: si se deja vacío, uno al azar) y la clase de 3
  personajes. La clase se valida con una lista blanca y el personaje se crea con `$clase::crearAlAzar($nombre)`.
  El equipo enemigo es aleatorio.
- **R9. Combate:** número de ronda; tarjetas de los personajes de los dos equipos con barras de vida y, solo para
  los que implementan `UsaMana`, de maná; el personaje activo resaltado; los caídos atenuados. Formulario de jugada
  (atacar o habilidad especial, deshabilitada si no se puede usar, y objetivo entre los enemigos vivos). Botones
  *Deshacer* (si se puede) y *Rendirse* / *Nueva partida*. Historial, de la línea más reciente a la más antigua.
- **R10. Organización:** un fichero por clase, interfaz o trait en `clases/`, un `cargar.php` que los incluye en
  orden, y una `vista.php` con funciones que devuelven el HTML.

### Pistas

- Desde una subclase se puede acceder a los atributos `protected` de **otro** objeto de la jerarquía
  (`$objetivo->nombre`), porque el atributo está declarado en `Personaje`.
- `new static(...)` dentro de un método estático crea un objeto de la clase **desde la que se llamó** al método.
- `clone $objeto` copia los atributos. Si un atributo es un objeto (o un array de objetos), se copia la
  **referencia**, salvo que lo clones tú en `__clone()`.
- Prueba a modificar el campo oculto con las herramientas del navegador: la partida debe rechazarse.
- `unserialize()` no ejecuta el constructor: compruébalo con el contador estático.

### Para reflexionar

1. ¿Por qué `recibirDanio()` y `usarEspecial()` son `final`?
2. ¿Por qué el maná se ha resuelto con interfaz + trait y no añadiendo una clase intermedia `PersonajeMagico`? ¿Qué
   pasaría si quisiéramos un *Paladín* que es un guerrero con maná?
3. ¿Qué ocurriría con "deshacer" si `Equipo` no tuviera `__clone()`?

### Ampliaciones opcionales

- Deshacer varios niveles (una pila de copias limitada a 5).
- Un quinto tipo de personaje que use el trait `ConMana` sin cambiar el resto del código.

## Ejemplo de criterios de puntuación

| Criterio | Puntos |
|---|---:|
| 1. Clase abstracta `Personaje`: atributos protegidos, validación, métodos abstractos y `__toString`. | 1,00 |
| 2. `__get` / `__set`: lectura sí, escritura no. | 0,75 |
| 3. `recibirDanio()` final y método plantilla `usarEspecial()`. | 0,75 |
| 4. `crearAlAzar()` con `new static`, `aleatorio()` y contador estático por clase. | 0,75 |
| 5. Interfaz `UsaMana` + trait `ConMana` usados por Mago y Sanador. | 1,00 |
| 6. Las cuatro clases concretas con su habilidad especial. | 1,00 |
| 7. `Equipo` con `Countable` y `__clone` profundo. | 0,75 |
| 8. `Combate::jugar()`: turnos, IA, rondas, fin de partida y jugadas no válidas sin efectos. | 1,50 |
| 9. Deshacer con `clone`. | 0,75 |
| 10. `Partida`: serialización, firma HMAC, `hash_equals` y `allowed_classes`. | 1,00 |
| 11. Página: nueva partida, tablero, formulario de jugada, historial; organización en ficheros. | 0,50 |
| 12. Respuestas a las preguntas de reflexión y comentarios. | 0,25 |
| **Total** | **10,00** |

## Solución propuesta

En [`SOLUCION-01`](SOLUCION-01/). Pon un punto de ruptura en `Combate::jugar()` y observa, en el panel VARIABLES,
que `$copia` y `$this` tienen personajes **distintos** (identificadores de objeto diferentes) gracias a los
`__clone()` de `Combate` y `Equipo`.
