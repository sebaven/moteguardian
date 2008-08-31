<p>Resultado de la ejecución del comando delete de ftp: 
<?php
	$con = ftp_connect("200.69.243.17","21","1000");
	ftp_login($con,"ftp_factory","Facturitas");
	//ftp_nlist($con,".");
	ftp_chdir($con,"srdfip/SRDFIP_ARIEL/CP_02/");
	$res = ftp_get($con,"c:\largo.dat","./big.dat",FTP_BINARY,0);
	var_dump($res);
	if ($res) echo "El archivo se bajo con éxito!";
		else echo "El archivo no se pudo bajar =(";
?>
</p>