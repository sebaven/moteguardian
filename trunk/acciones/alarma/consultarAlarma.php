<?php

$conexion = mysql_connect("localhost", "root", "");
mysql_select_db("moteguardian",$conexion);

$sql = 
		"SELECT id ".
		"FROM log_alarma ".
		"WHERE timestamp_fin IS NULL ".
			"AND baja_logica = '0' ";


$res = mysql_query($sql, $conexion);
if(mysql_affected_rows($conexion)>0){
	echo "alarma";
}
mysql_close($conexion);

?>