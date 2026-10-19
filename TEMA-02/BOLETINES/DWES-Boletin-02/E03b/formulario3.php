<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - El bucle llegaba hasta numero-2: con 2147483647 se agotaba el tiempo de ejecución. Ahora hasta la raíz.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */

 echo <<<SALIDA
 <div class="entrada">
     <p>
         <label for="numero" id="textoNumero" >Introduzca un número entero mayor de 1:</label>                    
         <input type="text" id="numero" name="numero" value="{$numero}" disabled/>
     </p>

     <!--  Estado autómata -->
     <input type="hidden" name="estado" value="{$estado}"/>
     <input type="hidden" name="numero" value="{$numero}"/>

     <!--  Botonera -->
     <button type="submit">Enviar</button>      
     <button type="reset" ><a href="{$_SERVER['SCRIPT_NAME']}" style="text-decoration: none;">Borrar</a></button>
 </div>
SALIDA;  

echo "<div class=\"salida\">";
$mensaje = "<p>El número es primo</p>";
// El siguiente bucle se puede optimizar,  si utilizamos la propiedad matemática:
//   N es primo si N no es divisible por cualquier entero (primo) <= raíz 
//   cuadrada de N
//   (es decir, en vez de $numero - 1, podríamos poner <= floor(sqrt(numero))                
for($i = 2; $i * $i <= $numero; $i++) {   // CORREGIDO: hasta la raíz (antes 2147483647 agotaba el tiempo)
    if ( $numero % $i === 0 ) {
        $mensaje =  "<p>El número NO es primo</p>";
        break;
    }
}

echo $mensaje;
echo "</div>";
?>