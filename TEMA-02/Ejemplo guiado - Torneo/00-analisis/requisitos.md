# Torneo de futbolín del Bar Baio — Análisis

## Enunciado (lo que pide el cliente)

> "Quiero que los equipos se puedan apuntar al torneo de futbolín desde la web. Cada equipo tiene un nombre y dos
> jugadores. Necesito un correo para avisarles y, si quieren, un teléfono. Hay tres categorías: mixta, juvenil y
> sénior. Caben 16 equipos como mucho. En la misma página quiero ver los equipos que ya están apuntados."

## Requisitos funcionales (RF): QUÉ debe hacer

| Id | Requisito |
|---|---|
| RF1 | Mostrar un formulario de inscripción con: nombre del equipo, jugador 1, jugador 2, correo electrónico, teléfono (opcional) y categoría. |
| RF2 | Validar los datos al enviarlos: nombre del equipo de 3 a 30 caracteres (letras, números y espacios) y no repetido; nombres de jugadores de 2 a 40 letras (con espacios, apóstrofos y guiones) y distintos entre sí; correo válido; teléfono de 9 cifras que empiece por 6, 7, 8 o 9 (se admiten espacios); categoría de la lista. |
| RF3 | Si hay errores, volver a mostrar el formulario **con los datos escritos** y un mensaje junto a cada campo incorrecto. |
| RF4 | Si todo es correcto, guardar la inscripción (con la fecha y la hora) y mostrar un mensaje de confirmación. Recargar la página no debe inscribir dos veces el mismo equipo. |
| RF5 | Mostrar la lista de equipos inscritos (nombre, jugadores, categoría y fecha) y las plazas libres. |
| RF6 | Cuando haya 16 equipos, no mostrar el formulario y avisar de que el torneo está completo. |

## Requisitos no funcionales (RNF): CÓMO debe hacerlo

| Id | Requisito |
|---|---|
| RNF1 | PHP 8.2 sin frameworks ni base de datos: las inscripciones se guardan en un fichero JSON. |
| RNF2 | Seguridad: todo dato que se muestra en la página se escapa con `htmlspecialchars` (XSS); no se confía en la validación del navegador; el fichero se escribe con bloqueo (`LOCK_EX`). |
| RNF3 | Funciona sin JavaScript. Página en UTF-8 y en español. |
| RNF4 | Accesible: cada campo tiene su `<label>` y los errores se muestran como texto junto al campo. |
| RNF5 | Código organizado: la lógica en funciones (`funciones.php`), la página en `index.php`, los estilos en `estilos.css`. |
| RNF6 | Mantenible: los límites (16 equipos, longitudes) y las categorías están en constantes. |

## Datos

| Dato | Tipo en PHP | Dónde vive |
|---|---|---|
| Datos del formulario (6 campos) | array asociativo de `string` | petición (`$_POST`) |
| Errores de validación | array asociativo `campo => mensaje` | tiempo de ejecución |
| Un equipo inscrito | array asociativo (`nombre`, `jugadores` (lista de 2), `email`, `telefono`, `categoria`, `fecha`) | fichero |
| Todos los equipos | lista (array indexado) de equipos | fichero `datos/equipos.json` (persistencia) |
| Máximo de equipos, categorías | constantes | código |
