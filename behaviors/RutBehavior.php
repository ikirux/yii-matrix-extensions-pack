<?php

class RutBehavior extends CBehavior
{
    public function formatRut($rut)
    {
    	// Primero vamos si es menos o mayor a 9 millones
        if (($largo = strlen($rut)) == 8) {
        	$rut = substr($rut, 0, 1) . '.' . substr($rut, 1, 3) . '.' . substr($rut, 4, 3) . '-' . substr($rut, 7, 1);
        } else {
         	$rut = substr($rut, 0, 2) . '.' . substr($rut, 2, 3) . '.' . substr($rut, 5, 3) . '-' . substr($rut, 8, 1);
        }

        return $rut;
    }
}