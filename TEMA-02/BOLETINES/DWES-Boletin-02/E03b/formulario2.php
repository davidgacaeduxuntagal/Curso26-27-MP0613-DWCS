<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - La etiqueta indica el máximo de 12 cifras.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
   $numero = "";
   echo <<<SALIDA
   <div class="entrada">
       <p>
           <label for="numero" id="textoNumero" style="color: red">Introduzca un NÚMERO ENTERO MAYOR DE 1 (máximo 12 cifras):</label>
           <input type="text" id="numero" name="numero" value="{$numero}"/>
       </p>

       <!--  Estado autómata -->
       <input type="hidden" name="estado" value="{$estado}"/>                    

       <!--  Botonera -->
       <button type="submit">Enviar</button>      
       <button type="reset" ><a href="{$_SERVER['SCRIPT_NAME']}" style="text-decoration: none;">Borrar</a></button>
   </div>
SALIDA;            
?>