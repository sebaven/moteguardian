<?php
/**
 * @date 21/05/2008
 * @version 1.0
 * @author glerendegui
 */

define("BASE_DIR_RELATIVO","../");

if(!defined("BASE_DIR_RELATIVO")) {
	die("ERR-CONF-Inclusión indebida de fichero global.php\n");
}

include_once(BASE_DIR."comun/defines_app.php");
include_once(BASE_DIR."ionix/config/ionix-config.php");
include_once(BASE_DIR."ionix/data/Db.php"); 
include_once(BASE_DIR."ionix/data/DbMySql.php"); 
include_once(BASE_DIR."ionix/data/ConnectionManager.php");
include_once(BASE_DIR."ionix/data/AbstractEntity.php");
include_once(BASE_DIR."ionix/data/AbstractDAO.php");
include_once(BASE_DIR."comun/Fecha.php");
include_once(BASE_DIR."clases/util/clase.FechaHelper.php");
include_once(BASE_DIR."clases/dao/dao.Planificacion.php");
include_once(BASE_DIR."clases/dao/dao.Actividad.php");


?>
<form method="post">
Fecha Desde:<br/>
A&ntilde;o: <input type="text" size="2" name="fecha_desde_anio" value="<?=$_POST['fecha_desde_anio'] ?>"/>
Mes:	<input type="text" size="2" name="fecha_desde_mes" value="<?=$_POST['fecha_desde_mes'] ?>" />
Dia: <input type="text" size="2" name="fecha_desde_dia" value="<?=$_POST['fecha_desde_dia'] ?>" />
Hora: <input type="text" size="2" name="fecha_desde_hora" value="<?=$_POST['fecha_desde_hora'] ?>" />
Minutos: <input type="text" size="2" name="fecha_desde_minutos" value="<?=$_POST['fecha_desde_minutos'] ?>" />
Segundos: <input type="text" size="2" name="fecha_desde_segundos" value="<?=$_POST['fecha_desde_segundos'] ?>" /> <br/>
Fecha Hasta:<br/>
A&ntilde;o: <input type="text" size="2" name="fecha_hasta_anio" value="<?=$_POST['fecha_hasta_anio'] ?>" />
Mes:	<input type="text" size="2" name="fecha_hasta_mes" value="<?=$_POST['fecha_hasta_mes'] ?>" />
Dia: <input type="text" size="2" name="fecha_hasta_dia" value="<?=$_POST['fecha_hasta_dia'] ?>" />
Hora: <input type="text" size="2" name="fecha_hasta_hora" value="<?=$_POST['fecha_hasta_hora'] ?>" />
Minutos: <input type="text" size="2" name="fecha_hasta_minutos" value="<?=$_POST['fecha_hasta_minutos'] ?>" />
Segundos: <input type="text" size="2" name="fecha_hasta_segundos" value="<?=$_POST['fecha_hasta_segundos'] ?>" /> <br/>
<input type="submit" value="probar" />
</form>
<?
if(!empty($_POST)) {
	echo "Procesando <br/>";
	$fechaDesde = new Fecha();
	$strFechaDesde = $_POST['fecha_desde_anio']."-".$_POST['fecha_desde_mes']."-".$_POST['fecha_desde_dia']." ".$_POST['fecha_desde_hora'].":".$_POST['fecha_desde_minutos'].":".$_POST['fecha_desde_segundos'];
	$fechaDesde->loadFromString($strFechaDesde);
	echo "Fecha desde = ".$fechaDesde->toString()."<br/>";
	$fechaHasta = new Fecha();
	$strFechaHasta = $_POST['fecha_hasta_anio']."-".$_POST['fecha_hasta_mes']."-".$_POST['fecha_hasta_dia']." ".$_POST['fecha_hasta_hora'].":".$_POST['fecha_hasta_minutos'].":".$_POST['fecha_hasta_segundos'];
	$fechaHasta->loadFromString($strFechaHasta);
	echo "Fecha hasta = ".$fechaHasta->toString()."<br/><br/><br/>";
	
	$actividadDAO = new ActividadDAO();
	$actividades = $actividadDAO->filterByFecha($fechaDesde,$fechaHasta);
	
	
	echo "Actividades a lanzar: <br/>";
	foreach($actividades as $actividad) {
		if(empty($actividad->id)) continue;
		echo "Actividad ID = ".$actividad->id." --- ".$actividad->nombre."<br/>";
	}
}


 ?>