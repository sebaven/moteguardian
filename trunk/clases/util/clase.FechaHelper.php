<?php

include_once(BASE_DIR."comun/Fecha.php");

class FechaHelper {
	
	function fechaDiffDiasCompletos($fechaDesde,$fechaHasta) {
		$dias = array();
		// Mas de una semana, todos los dias
		if(FechaHelper::fechaDiffSemanaCompleta($fechaDesde,$fechaHasta)) {
			$dias[] = CONST_DIA_LUNES;
			$dias[] = CONST_DIA_MARTES;
			$dias[] = CONST_DIA_MIERCOLES;
			$dias[] = CONST_DIA_JUEVES;
			$dias[] = CONST_DIA_VIERNES;
			$dias[] = CONST_DIA_SABADO;
			$dias[] = CONST_DIA_DOMINGO;
			return $dias;
		}
		$diaDesde = $fechaDesde->diaDeLaSemana;
		$diaHasta = $fechaHasta->diaDeLaSemana;
		// Mismo dia
		if($diaDesde == $diaHasta) return $dias;
		// Dias en el medio enteros
		$diaCorrido = $diaDesde+1;
		if($diaCorrido == 7) $diaCorrido = 0;
		while($diaCorrido != $diaHasta) {
			$dias[] = FechaHelper::diaDeLaSemanaToString($diaCorrido);
			$diaCorrido++;
			if($diaCorrido == 7) $diaCorrido=0;
		}
		return $dias;
	}
	
	function diaDeLaSemanaToString($diaDeLaSemana) {
		switch($diaDeLaSemana) {
			case 0:	return CONST_DIA_DOMINGO; break;
			case 1:	return CONST_DIA_LUNES; break;
			case 2:	return CONST_DIA_MARTES; break;
			case 3:	return CONST_DIA_MIERCOLES; break;
			case 4:	return CONST_DIA_JUEVES; break;
			case 5:	return CONST_DIA_VIERNES; break;
			case 6:	return CONST_DIA_SABADO; break;
		}
		return "Error";
	}
	

	function fechaDiffSemanaCompleta($fechaDesde,$fechaHasta) {
		$timestampDesde = $fechaDesde->toTimestamp();
		$timestampHasta = $fechaHasta->toTimestamp();
		return (($timestampHasta-$timestampDesde) > 60*60*24*7);
	}
	
}

?>