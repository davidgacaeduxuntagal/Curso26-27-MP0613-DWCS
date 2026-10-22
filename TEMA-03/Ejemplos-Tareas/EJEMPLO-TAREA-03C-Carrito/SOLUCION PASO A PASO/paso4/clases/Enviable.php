<?php
/*
 * Enviable.php
 *  Interfaz para los productos que se pueden enviar por mensajería. Los que NO la implementan
 *  (los alimentos frescos) solo se pueden recoger en la tienda.
 */
interface Enviable
{
    /**
     * Peso del producto en kilos (para calcular los gastos de envío).
     */
    public function peso(): float;
}
