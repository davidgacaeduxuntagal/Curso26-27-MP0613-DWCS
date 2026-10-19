<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Se añade el párrafo del cociente (lo pide el enunciado).
 */
        echo <<<MARCA
        <!--  Estado autómata -->
        <input type="hidden" name="estado" value="{$estado}"/>        
        <p>
            <label for="numero1">Introduzca el primer número:</label>
            <input type="number" id="numero1" name="numero1" />
        </p>
        <p>
            <label for="numero2">Introduzca el segundo número:</label>
            <input type="number" id="numero2" name="numero2" />
        </p>
        <p style="color: red">Rellene ambos campos, por favor</p>
        <p id="suma">&nbsp;</p>
        <p id="resta">&nbsp;</p>
        <p id="producto">&nbsp;</p>
        <p id="cociente">&nbsp;</p>  
        <p id="resto">&nbsp;</p>    
MARCA;
?>