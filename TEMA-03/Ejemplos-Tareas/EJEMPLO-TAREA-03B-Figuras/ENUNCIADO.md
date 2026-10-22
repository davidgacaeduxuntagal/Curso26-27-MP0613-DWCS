# TAREA-03 (nivel BÁSICO): Figuras geométricas

**CFGS Desarrollo de Aplicaciones Web — MP0613 Desarrollo web en entorno servidor — Tema 3 (POO en PHP)**

## Caso práctico

El departamento de Matemáticas quiere una pequeña web para que el alumnado de la ESO practique áreas y perímetros.
**Carlos** decide aprovecharla para aplicar lo que está aprendiendo de programación orientada a objetos: cada tipo
de figura será una clase, todas compartirán una clase base y sabrán dibujarse a sí mismas.

## Enunciado

Crea una jerarquía de clases que modele figuras geométricas y una página `index.php` que las utilice.

### Parte 1: jerarquía de clases (carpeta `clases/`, un fichero por clase)

- **R1. Interfaz `Dibujable`** con dos métodos:
  - `dimensionMaxima(): float`: la mayor medida (ancho o alto) que ocupa la figura;
  - `svg(float $escala, int $tamano = 140): string`: devuelve el código SVG que dibuja la figura, centrada en un
    cuadrado de `$tamano` píxeles, usando `$escala` píxeles por centímetro.
- **R2. Clase abstracta `Figura`**, que implementa `Dibujable`:
  - constante `UNIDAD = 'cm'` y constante `COLORES` con 5 colores (nombre => código HTML);
  - atributo protegido `color`, con *getter* y *setter*. El *setter* lanza una `InvalidArgumentException` si el
    color no es uno de los permitidos;
  - métodos abstractos `nombre()`, `area()`, `perimetro()` y `medidas()` (texto con las medidas, por ejemplo
    `"r = 3,00 cm"`);
  - `__toString()`, implementado **una sola vez** en `Figura`, que devuelve, por ejemplo,
    `"Círculo azul (r = 3,00 cm): área 28,27 cm², perímetro 18,85 cm"`;
  - un **contador estático por clase**: `Figura::numeroCreadas(Circulo::class)` devuelve los círculos creados y
    `Figura::numeroCreadas()`, el total;
  - un método estático `generarAlAzar()` que crea una figura de un tipo elegido al azar.
- **R3. Clases concretas**, cada una con sus atributos privados o protegidos, *getters* y *setters* con
  validación (medidas mayores que 0) y su propio `generarAlAzar()`, que devuelve una figura de su tipo con medidas
  y color aleatorios:
  - `Circulo` (radio);
  - `Rectangulo` (base y altura);
  - `Cuadrado`, que **hereda de `Rectangulo`**, es una clase `final` y redefine los *setters* de base y altura para
    que siga siendo un cuadrado;
  - `Triangulo` (lados a, b y c): debe comprobar la desigualdad triangular, calcular el área con la fórmula de
    Herón y tener un método propio `tipo()` que devuelva "equilátero", "isósceles" o "escaleno".
- **R4. Objetos siempre válidos**: si los datos son incorrectos, el constructor lanza la excepción y el objeto
  **no** debe contarse en el contador estático.

### Parte 2: página `index.php`

- **R5. Formulario (GET)** con:
  - número de figuras al azar (0 a 20, por defecto 8);
  - criterio de ordenación: área, perímetro o tipo de figura;
  - una figura "a medida" opcional: tipo, hasta 3 medidas (admitiendo coma decimal) y color.

  Se validarán los datos y, si la figura a medida no es válida, se mostrará el mensaje de la excepción
  (`try … catch`).
- **R6. Listado**: se muestran todas las figuras ordenadas con su dibujo SVG y su `__toString()`. Todas se
  dibujan con la **misma escala**, para que se vean proporcionadas. Para los triángulos se muestra además su tipo
  (`instanceof`). La figura a medida aparece resaltada.
- **R7. Resumen**: área total (con `array_reduce`) y número de objetos creados de cada clase y en total (con los
  contadores estáticos).
- **R8. Polimorfismo**: el código de `index.php` no debe preguntar por el tipo de figura para calcular áreas,
  perímetros, dibujar u ordenar. Solo se usa `instanceof` para la información propia de los triángulos.

### Pistas

- `static::class` dentro de un método de `Figura` devuelve el nombre de la clase **real** del objeto (*late static
  binding*): es la clave del contador por clase.
- Un método estático se puede llamar con el nombre de la clase en una variable: `$clase::generarAlAzar()`.
- Una subclase puede redefinir un método y devolver un tipo **más concreto**: si `Rectangulo::generarAlAzar()`
  devuelve `Rectangulo`, `Cuadrado::generarAlAzar()` puede devolver `Cuadrado`.
- Para dibujar el triángulo, coloca el lado c en horizontal desde (0, 0) hasta (c, 0). El tercer vértice está en
  x = (b² − a² + c²) / 2c, y = √(b² − x²). En SVG la y crece hacia abajo.
- SVG básico: `<circle cx cy r fill>`, `<rect x y width height fill>` y `<polygon points="x1,y1 x2,y2 x3,y3" fill>`.

## Ejemplo de criterios de puntuación

| Criterio | Puntos |
|---|---:|
| 1. Interfaz `Dibujable` y clase abstracta `Figura` con constantes, atributos y métodos abstractos. | 1,50 |
| 2. `__toString()` único en `Figura` aprovechando el polimorfismo. | 0,75 |
| 3. Contador estático por clase con `static::class` y `numeroCreadas()`. | 1,00 |
| 4. Clases `Circulo` y `Rectangulo` con *getters* y *setters* validados. | 1,00 |
| 5. `Cuadrado` como subclase `final` de `Rectangulo` con *setters* redefinidos. | 1,00 |
| 6. `Triangulo`: desigualdad triangular, Herón y `tipo()`. | 1,00 |
| 7. `generarAlAzar()` en cada clase y en `Figura`. | 0,75 |
| 8. Dibujo SVG de cada figura con escala común. | 1,00 |
| 9. Formulario, validación y figura a medida con `try … catch`. | 1,00 |
| 10. Ordenación (`usort`) y resumen (`array_reduce`, contadores). | 0,75 |
| 11. Comentarios, organización en ficheros y diseño. | 0,25 |
| **Total** | **10,00** |

## Solución propuesta

En [`SOLUCION-01`](SOLUCION-01/). Ejecútala en modo depuración y pon un punto de ruptura en el constructor de
`Figura`: observa el valor de `static::class` cuando se crea un `Cuadrado`, y el orden en que se ejecutan los
constructores de `Cuadrado`, `Rectangulo` y `Figura`.
