<?php
/*
$sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

if(!socket_connect($sock,"200.69.243.17",21)) {
	die("Imposible conectar\n");
}

var_dump(socket_read($sock,100));
socket_write($sock,"USER ftp_factory\r\n");
var_dump(socket_read($sock,100));
socket_write($sock,"PASS Facturitas\r\n");
var_dump(socket_read($sock,100));

die();

*/

$puerto_comandos = 3036;
$puerto_datos = 3040;


$sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

if(!socket_bind($sock,"0.0.0.0",$puerto_comandos)) {
	die("Imposible");
}

socket_listen($sock);

$sock2 = socket_accept($sock);
socket_set_block($sock2);
$leido = "";

socket_write($sock2,"220 Soy un falso FTP\r\n");
socket_recv($sock2,$leido,100,0);

socket_write($sock2,"331 Password required\r\n");
socket_recv($sock2,$leido,100,0);
socket_write($sock2,"230 User logged in\r\n");
socket_recv($sock2,$leido,100,0);
socket_write($sock2,"257 \"carpeta pepito\" is current work directory\r\n");
socket_recv($sock2,$leido,100,0);
socket_write($sock2,"200 Type set to A\r\n");
socket_recv($sock2,$leido,100,0);
socket_write($sock2,"227 Entering Passive Mode(127,0,0,1,".floor($puerto_datos / 256).",".($puerto_datos % 256).")\r\n");
socket_recv($sock2,$leido,100,0);

$sock_data = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
if(!socket_bind($sock_data,"0.0.0.0",$puerto_datos)) {
	die("Imposible");
}

socket_listen($sock_data);

$sock2_data = socket_accept($sock_data);
socket_recv($sock2_data,$leido,100,0);

var_dump($leido);
/*

$ip = "";
socket_getpeername($sock2,$ip);
echo "Conectando a $ip:$puerto\n";

if(!(socket_connect($sock_data,$ip,$puerto))) {
	die("Imposible conectar");
}
*/
/*socket_write($sock_data,"SDSDSD\r\n");
socket_read($sock_data,$leido);
*/


socket_shutdown($sock);
socket_shutdown($sock_data);
?>