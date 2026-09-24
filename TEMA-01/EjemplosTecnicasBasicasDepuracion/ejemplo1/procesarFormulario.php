<!DOCTYPE html>
<!-- EJEMPLO  respuesta del servidor al formulario  -->

<html lang="es">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
	<link rel="icon" type="image/png" href="favicon.png">

	
    <title>..:Ejemplo 01-form:..</title>

  </head>
  
  <body>
	<h1>EJEMPLO respuesta del servidor</h1>
    
	<?php
	  // La variables global $_POST te permite acceder a los datos enviados por el metodo POST the POST method 
	  // Para acceder a datos remitidos con el método GET, puedes usar la variable $_GET
	  $usuario = htmlspecialchars($_POST['nombre_usuario']);
	  $email   = htmlspecialchars($_POST['email_usuario']);
	  $mensaje = htmlspecialchars($_POST['mensaje_usuario']);

	  echo  'Hola ' . $usuario . ' en la dirección de correo: '. $email . '<br>' ;
	  echo 'Hemos recibido tu mensaje: ' . $mensaje;
	?>
  </body>
</html>


