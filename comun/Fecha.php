<?

class Fecha {
	var $anio = 2000;
	var $mes = 1;
	var $dia = 1;
	var $hora = 0;
	var $minutos = 0;
	var $segundos = 0;
	var $diaDeLaSemana = 0; // 0 Domingo - 6 Sabado
	
	function Fecha() {
	}

	// Formato YYYY-MM-DD hh:mm
	function loadFromString($str) {
		$fecha = strtotime($str);
		if(!$fecha) return false;
		$this->loadFromTimestamp($fecha);
		return true;	
	}
	
	function loadFromArray($datos) {
		$this->anio = $datos['year'];
		$this->mes = $datos['mon'];
		$this->dia = $datos['mday'];
		$this->hora = $datos['hours'];
		$this->minutos = $datos['minutes'];
		$this->segundos = $datos['seconds'];
		$this->diaDeLaSemana = $datos['wday'];
		return true;
	}
	
	function loadFromTimestamp($timestamp) {
		$datos = getdate($timestamp);
		$this->loadFromArray($datos);
	}
	
	function loadFromNow() {
		$datos = getdate();
		$this->loadFromArray($datos);
	}
	
	function loadFromYesterday() {
		$timestamp = time();
		$timestamp = $timestamp - (1 * 24 * 60 * 60);
		$this->loadFromTimestamp($timestamp);
	}

	function loadFromTomorrow() {
		$timestamp = time();
		$timestamp = $timestamp + (1 * 24 * 60 * 60);
		$this->loadFromTimestamp($timestamp);
	}
	
	
	function toString() {
		$str = $this->dateToString()." ".$this->timeToString();
		return $str;
	}
	
	function dateToString() {
		$str = $this->yearToString()."-".$this->monthToString()."-".$this->dayToString();
		return $str;
	}
	
	function timeToString() {
		return $this->hourToString().":".$this->minutesToString().":".$this->secondsToString();
	}
	
	function toTimeStamp() {
		return mktime($this->hora,$this->minutos,$this->segundos,$this->mes,$this->dia,$this->anio);
	}
	
	function addSeconds($seconds) {
		$timestamp = strtotime($this->toString()." +".$seconds."second");
		$this->loadFromTimestamp($timestamp);
	}

	function yearToString($digitos=4) {
		$str = sprintf("%04d",$this->anio);
		$str = substr($str,4-$digitos,$digitos);
		return $str;
	}
	
	function monthToString() {
		$str = sprintf("%02d",$this->mes);
		return $str;
	}
	
	function dayToString() {
		$str = sprintf("%02d",$this->dia);
		return $str;
	}
	
	function hourToString() {
		$str = sprintf("%02d",$this->hora);
		return $str;
	}
	
	function minutesToString() {
		$str = sprintf("%02d",$this->minutos);
		return $str;
	}
	
	function secondsToString() {
		$str = sprintf("%02d",$this->segundos);
		return $str;		
	}
	
}

?>