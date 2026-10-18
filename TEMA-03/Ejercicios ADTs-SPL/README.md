# Tipos de datos abstractos (ADT) con la SPL

Apuntes, ejemplos y problemas de las estructuras de datos de la *Standard PHP Library*. Todo está probado con
**PHP 8.2** (XAMPP en Windows).

| Carpeta | ADT | Clase SPL | Apuntes |
|---|---|---|---|
| `01-Stack-SplStack` | Pila | `SplStack` | 5 págs. |
| `02-Queue-SplQueue` | Cola | `SplQueue` | 5 págs. |
| `03-DoublyLinkedList-SplDoublyLinkedList` | Lista doblemente enlazada | `SplDoublyLinkedList` | 5 págs. |
| `04-PriorityQueue-SplPriorityQueue` | Cola de prioridad | `SplPriorityQueue` | 5 págs. |
| `05-MinHeap-SplMinHeap` | Montículo mínimo | `SplMinHeap` | 4 págs. |
| `06-MaxHeap-SplMaxHeap` | Montículo máximo | `SplMaxHeap` | 4 págs. |
| `07-CustomHeap-SplHeap` | Montículo personalizado | `SplHeap` | 6 págs. |
| `08-FixedArray-SplFixedArray` | Array de tamaño fijo | `SplFixedArray` | 5 págs. |
| `09-ObjectStorage-SplObjectStorage` | Conjunto / mapa de objetos | `SplObjectStorage` (y `WeakMap`) | 7 págs. |

## Estructura de cada carpeta

```
NN-Nombre-Clase/
  Apuntes Nombre/        Apuntes-Clase.pdf y .html (figura, ejemplos, ejercicio resuelto, autoevaluación)
  Ejercicios Nombre/
    e01-Clase.php        la API, paso a paso
    e02-Clase.php        aplicaciones reales
    problemas/
      e01-Clase_problema.php   e01-Clase_problema_solucion.php
      e02-Clase_problema.php   e02-Clase_problema_solucion.php
```

- Los apuntes siguen el formato de los del módulo (Caso práctico con Carlos, María y Juan en BK Programación,
  *Debes conocer*, *Para saber más*, *Recomendación*, *Ejercicio resuelto*, *Autoevaluación*).
- En los ejemplos, antes de cada concepto hay un comentario con el **ENLACE** a la documentación oficial, el
  **ENUNCIADO EJEMPLO** y notas; los comentarios a la derecha muestran el resultado real con PHP 8.2.
- Los problemas (hasta 5 por fichero) se resuelven por separado y el fichero sin resolver se ejecuta sin errores.
- Todos usan `utilidades.php` (en esta carpeta). Su función `ver()` muestra cada estructura SPL con su contenido en
  el orden en que saldría (`SplStack(3) cima -> [...] <- fondo`, `SplMinHeap(5) en orden de salida: [...]`),
  trabajando sobre una copia para no modificarla; `verError()` muestra el error de un código que debe fallar sin
  detener el ejemplo.

## Aplicaciones que se trabajan

- **Pila**: deshacer / rehacer, botón "Atrás", HTML y paréntesis equilibrados, notación polaca inversa, recorrido
  en profundidad, laberintos.
- **Cola**: cola de correos con reintentos, *round-robin*, camino más corto (BFS), últimos N mensajes, Josefo,
  simulación de una ventanilla, grados de separación, media móvil.
- **Lista doble**: deque de pedidos urgentes, palíndromos, lista ordenada, caché LRU, lista circular, historial con
  atrás y adelante, máximo de una ventana deslizante, fusionar listas.
- **Cola de prioridad**: triaje de urgencias, mensajes programados, Dijkstra (carreteras gallegas y autobuses),
  impresora con prioridades, simulación por eventos.
- **Montículos**: top K, los K menores, fusionar registros de varios servidores, asignar trabajos a máquinas, salas
  de reuniones, mediana en tiempo real con dos montículos, heapsort, rankings con desempate.
- **Montículo personalizado**: pedidos por fecha e importe, orden alfabético español con `Collator`, comparador
  como parámetro, montículos corruptos, gestor de tareas, ranking ponderado, ascensor.
- **Array fijo**: tres en raya, butacas de un cine, histograma, buffer circular, hundir la flota.
- **Conjunto / mapa de objetos**: usuarios conectados, operaciones de conjuntos, `getHash`, carrito objeto →
  cantidad, patrón Observador (`SplSubject` / `SplObserver`), red de seguidores, caché con `WeakMap`.

## Comportamientos de PHP 8.2 que se explican (comprobados al preparar el material)

- Recorrer un `SplHeap` o una `SplPriorityQueue` con `foreach` **los vacía** (se recorre una copia con `clone`).
- `SplPriorityQueue` **no** respeta el orden de llegada con prioridades iguales (se desempata con `[prioridad, -orden]`).
- En una `SplStack`, `$pila[0]` es la cima; `SplStack`, `SplQueue` y `SplDoublyLinkedList` **no tienen `toArray()`**.
- En una `SplQueue`, `bottom()` es el frente y `top()` el final.
- `$fijo[$i]++` y `+=` no tienen efecto en un `SplFixedArray`.
- Hacer `detach()` dentro del `foreach` de un `SplObjectStorage` hace que se salte el siguiente elemento (también en
  el patrón Observador si un observador se da de baja en `update()`).
- Si `compare()` lanza una excepción, el montículo queda corrupto.
- En las cuadrículas de strings, `$fila[-1]` es el último carácter (el BFS "daba la vuelta" al mapa).
