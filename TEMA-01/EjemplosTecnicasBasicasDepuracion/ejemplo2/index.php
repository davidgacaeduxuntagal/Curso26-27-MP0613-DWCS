<!DOCTYPE html>
<!-- EJEMPLO 4: respuesta del servidor al formulario  -->

<html lang="es">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
	<title>Ejemplo de acceso a base de datos utilizando PHP</title>
	
    <title>..:Ejemplo acceso a base de datos:..</title>

  </head>
  
  <body>
	<h1>Ejemplo de acceso a base de datos</h1>
    
	<?php
   // (1) Abrir una conexión al sistema gestor de base de datos:
   $conexion = new mysqli("localhost","root","abc123.","ejemplophp2");
   $conexion->set_charset("utf8");
   
   // (2) Ejecutar una consulta contra la base de datos: 
   $resultado = $conexion->query ("SELECT * FROM clientes");

   // (3) Ir recogiendo cada fila del resultset
   while ($fila = $resultado->fetch_object())
   {
     // (4) Imprimir cada columna de la fila actual:
      foreach ($fila as $columna)
         echo "{$columna} " . " -- ";

      // Añadimos salto de línea al final de cada fila:
      echo "<br>" . "\n";
   }
	?>




  </body>
</html>


