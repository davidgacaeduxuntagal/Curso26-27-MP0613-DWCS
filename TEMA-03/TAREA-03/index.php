<?php
// Array para almacenar las clases
$clases = [];

// Cargamos todas las clases desde archivos PHP en el directorio actual
foreach (glob("class/*.php") as $archivo) {
    require_once $archivo;

    // Agregar el nombre de la clase (sin la extensión .php) al array para después llamar a los métodos estáticos con él
    if ($archivo !== "class/Persona.php" && $archivo !== "class/Alumnado.php" && $archivo !== "class/Trabajador.php" && $archivo !== "class/CargoDirectivo.php") {
        $clases[] = pathinfo($archivo, PATHINFO_FILENAME);
    }
}
?>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea UD03</title>
</head>

<body>
    <?php
    // Cree un array de 100 objetos al azar de los anteriores.
    $objetos = [];
    for ($i = 0; $i < 100; $i++) {
        $objetos[$i] = $clases[array_rand($clases)]::generarAlAzar(); // Llamamos al método estático que crea un objeto aleatorio.
        // Invoque para cada objeto el método trabajar().
        echo $objetos[$i] . " " . $objetos[$i]->trabajar() . "<br>";
    }
    echo "<br><hr>";
    foreach ($clases as $clase) {
        // Indique cuántos objetos se crearon de cada clase.
        echo "Cantidad de objetos de la clase $clase: " . $clase::numeroObjetosCreado() . "<br>";
    }
    ?>
</body>

</html>