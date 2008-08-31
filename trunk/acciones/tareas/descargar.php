<?php

function descargar($so_mp3){
/*
   $elArchivo = basename($so_mp3);
      
   Rheader( "Content-Type: application/octet-stream"); 
   header( "Content-Length: ".filesize($so_mp3)); 
   header( "Content-Disposition: attachment; filename=".$elArchivo.""); 
   readfile($so_mp3);
*/
	if(file_exists($so_mp3)){
		$arch = fopen($so_mp3,"r");	
		$buffer = 0;
		while (!feof($arch)) {
			$buffer = fgets($arch, 4096);		
			echo $buffer."<br/>";		
		}
	
		fclose($arch);	
		return true; 
	} else {
		echo "El fichero fue eliminado del servidor";
		return false;
	}
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8559-1"/>
</head>
<body style="font-family: monospace; font-size: 11px;">
<p>
<? descargar($_GET['archivo']); ?>
</p>
</body>
</html>