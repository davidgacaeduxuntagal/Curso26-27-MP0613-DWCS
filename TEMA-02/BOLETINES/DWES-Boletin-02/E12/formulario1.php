<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
           echo <<<SALIDA
           <div class="entrada">
               <p>
                   <label for="numero" id="textoNumero">Introduzca un número entero positivo:</label>
                   <input type="text" id="numero" name="numero" />
               </p>

               <!--  Estado autómata -->
               <input type="hidden" name="estado" value="{$estado}"/>


               <!--  Botonera -->
               <button type="submit">Enviar</button>      
               <button type="reset" ><a href="{$_SERVER['SCRIPT_NAME']}" style="text-decoration: none;">Borrar</a></button>
           </div>
SALIDA;

?>