# TAREA-03 (nivel MEDIO): Carrito de la compra orientado a objetos

**CFGS Desarrollo de Aplicaciones Web — MP0613 Desarrollo web en entorno servidor — Tema 3 (POO en PHP)**

## Caso práctico

Una tienda de productos gallegos quiere vender por Internet libros, alimentación y electrónica. Cada tipo de
producto tiene su propio IVA y sus reglas: los alimentos frescos no se envían por mensajería y tienen descuento
cuando están a punto de caducar, y la electrónica paga una ecotasa. **Juan** le pide a **Carlos** que lo modele con
clases, de forma que añadir un nuevo tipo de producto no obligue a tocar el carrito.

## Enunciado

Programa una tienda online con un catálogo y un carrito de la compra. El carrito debe conservarse entre peticiones
**sin usar `$_SESSION`**, **serializando** el objeto en un campo oculto del formulario.

### Parte 1: los productos (carpeta `clases/`, un fichero por clase o interfaz)

- **R1. Clase abstracta `Producto`** con:
  - atributos protegidos `codigo` (formato `AAA999`), `nombre` y `precio` sin IVA, validados en el constructor
    (`InvalidArgumentException`);
  - métodos abstractos `tipoIva(): float` y `categoria(): string`;
  - métodos que las subclases pueden redefinir: `precioSinIva()` y `detalles()` (texto con información propia
    del tipo de producto);
  - `precioConIva()` y `__toString()` comunes.
- **R2. Interfaz `Enviable`** con el método `peso(): float` (en kg). Solo la implementan los productos que se pueden
  enviar.
- **R3. Subclases:**

  | Clase | IVA | Atributos propios | Reglas |
  |---|---:|---|---|
  | `Libro` (Enviable) | 4 % | autor, peso | — |
  | `Alimento` (no Enviable) | 10 % | fecha de caducidad | 30 % de descuento si caduca en 3 días o menos |
  | `Electronica` (Enviable) | 21 % | años de garantía, peso | se suma una ecotasa fija de 1,50 € al precio sin IVA; ese método es `final` |

- **R4. Clase `Catalogo`**, con todo estático: los productos en un array `código => producto`, que se crea la
  primera vez que se necesita (carga perezosa), y los métodos `todos()`, `buscar($codigo)` y `porCategoria()`.
  No se pueden crear objetos `Catalogo` (constructor privado).

### Parte 2: el carrito

- **R5. Clase `LineaCarrito`**: un producto y su cantidad (1 a 20, validada). Calcula su subtotal sin IVA, su cuota
  de IVA y su subtotal.
- **R6. Clase `Carrito`**, que **implementa la interfaz `Countable`** de PHP (`count($carrito)` devuelve el total de
  unidades), con los métodos:
  - `anadir($codigo, $cantidad)`: si el producto ya está, se suman las unidades;
  - `cambiarCantidad($codigo, $diferencia)`: si llega a 0, se quita la línea;
  - `quitar($codigo)` y `vaciar()`;
  - `totalSinIva()`, `totalProductos()` y `desgloseIva()` (base y cuota agrupadas por tipo de IVA);
  - `esEnviable()`: el pedido se puede enviar si **todos** los productos son `Enviable` (`instanceof`);
  - `gastosEnvio()`: `null` si solo se puede recoger en tienda; gratis a partir de 50 € de productos; si no,
    3,99 € + 0,50 € por kg (redondeando los kg hacia arriba);
  - `total()`.

### Parte 3: guardar el carrito entre peticiones

- **R7. Serialización.** El carrito se guarda en un campo oculto con `base64_encode(serialize($carrito))` y se
  recupera con `unserialize`, en un método estático `Carrito::desdeTexto($texto)` que devuelve el carrito o `null`
  si el texto no es válido.
- **R8. Seguridad. No te fíes del campo oculto:**
  - `LineaCarrito::__sleep()` hace que se serialicen **solo el código y la cantidad**. Así el precio nunca sale del
    servidor;
  - `LineaCarrito::__wakeup()` vuelve a buscar el producto en el catálogo y revalida la cantidad. Lanza una excepción
    si algo no es correcto;
  - `unserialize` se llama con la opción `allowed_classes`, permitiendo solo `Carrito` y `LineaCarrito`;
  - `Carrito::__wakeup()` comprueba que el array de líneas solo contiene objetos `LineaCarrito`.

  Si el carrito recibido no es válido, se muestra un aviso y se empieza con uno vacío.

### Parte 4: la página `index.php`

- **R9.** Catálogo agrupado por categorías: nombre, detalles, precio con IVA, tipo de IVA, aviso "solo recogida en
  tienda" para los no enviables, y una caja de cantidad con su botón *Añadir*.
- **R10.** Carrito: líneas con botones `−` / `+` / `✕`, número de unidades (`count`), desglose de IVA, gastos de
  envío (o "Solo recogida en tienda", o "Gratis"), total, y cuánto falta para el envío gratis. Botón *Vaciar*.
- **R11.** Los errores de validación de las clases (cantidad máxima, código inexistente...) se capturan con
  `try … catch` y se muestran al usuario.
- **R12.** `index.php` **no calcula ningún importe**: todo lo hacen los objetos.

### Pistas

- Un botón `<button type="submit" name="quitar" value="LIB001">` envía la acción en su nombre y el producto en su
  valor.
- Al pulsar *Intro* en una caja de texto, el navegador pulsa el **primer** botón del formulario.
- La clase `DateTimeImmutable` de PHP facilita trabajar con fechas: `(new DateTimeImmutable('today'))->diff($fecha)`.
- Mira el texto que genera `serialize($carrito)` en el depurador antes y después de añadir `__sleep()`.
- Para comprobar la seguridad, decodifica el campo oculto, cambia la cantidad a 500 y vuelve a codificarlo.

### Ampliaciones opcionales

- Un cupón de descuento (`VERANO10`) que se guarde también en el carrito serializado.
- Firmar el carrito serializado con `hash_hmac` para detectar cualquier modificación.

## Ejemplo de criterios de puntuación

| Criterio | Puntos |
|---|---:|
| 1. Clase abstracta `Producto` con validación, métodos abstractos y métodos redefinibles. | 1,25 |
| 2. Interfaz `Enviable` y subclases `Libro`, `Alimento` (oferta por caducidad) y `Electronica` (ecotasa `final`). | 1,50 |
| 3. `Catalogo` estático con carga perezosa y constructor privado. | 0,75 |
| 4. `LineaCarrito` con validación e importes. | 0,75 |
| 5. `Carrito` con sus operaciones e implementación de `Countable`. | 1,25 |
| 6. Desglose de IVA, envío según `Enviable` y total. | 1,00 |
| 7. Serialización en un campo oculto y `desdeTexto()`. | 1,00 |
| 8. `__sleep` / `__wakeup` y `allowed_classes`: el carrito no se puede manipular. | 1,25 |
| 9. Página: catálogo, carrito, botones, `try … catch` y ningún cálculo en `index.php`. | 1,00 |
| 10. Comentarios, organización y diseño. | 0,25 |
| **Total** | **10,00** |

## Solución propuesta

En [`SOLUCION-01`](SOLUCION-01/). Pon puntos de ruptura en `__sleep()` y `__wakeup()` de `LineaCarrito` y observa
cuándo se ejecutan en cada petición.
