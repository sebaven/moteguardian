<?

	/**
	 * Clase para obtener las fechas necesarias
	 */
	class DateHelper{

		/**
		 * Esta funcion devuelve la fecha de hoy en formato Y-m-d
		 * @access public
		 * @author dabiricha
		 * @return fecha de hoy en formato Y-m-d
		 */
		function getFechaHoy(){
			
			$timestamp = time();
			return date("Y-m-d",$timestamp);
		}
		
		/**
		 * Esta funcion devuelve la fecha de 7 dias posteriores al dia de hoy en formato Y-m-d
		 * @access public
		 * @author dabiricha
		 * @return fecha de 7 dias posteriores al dia de hoy en formato Y-m-d
		 */
		function getFechaAdelanteSieteDias(){
			
			$timestamp = time();
			return date("Y-m-d",$timestamp + (7 * 24 * 60 * 60));
		}
		
		/**
		 * Esta funcion devuelve la fecha de 7 dias anteriores al dia de hoy en formato Y-m-d
		 * @access public
		 * @author dabiricha
		 * @return fecha de 7 dias anteriores al dia de hoy en formato Y-m-d
		 */
		function getFechaAtrasSieteDias(){
			
			$timestamp = time();
			return date("Y-m-d",$timestamp - (7 * 24 * 60 * 60));
		}
	}
?>