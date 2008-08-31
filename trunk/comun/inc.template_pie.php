<?
?>
						</td>
		              </tr>
		            </table></td>
		          </tr>
		        </table></td>
		      </tr>
		    </table>
		      <table width="950" border="0" cellspacing="0" cellpadding="0">
		        <tr>
		          <td width="950" height="25" background="imagenes/bg_gradient25x1blue.jpg" align="center" class="Estilo2"> ©2008 SDRFiP. </td>
		        </tr>
		      </table></td>
		  </tr>
		</table>
		<script type="text/javascript" language="javascript">
			var currenttime = '<? print date("F d, Y H:i:s", time())?>'
			var montharray = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
			var serverdate = new Date(currenttime)
			
			function padlength(what){
				var output = (what.toString().length==1) ? "0"+what : what
				return output
			}
			
			function displaytime(){
				serverdate.setSeconds(serverdate.getSeconds()+1)
				var datestring="Fecha: "+montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()+" - "
				var timestring="Hora: "+padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
				document.getElementById("servertime").innerHTML=datestring+" "+timestring
			}
			
			window.onload=function(){
				setInterval("displaytime()", 1000)
			}	
		</script>
	</body>
</html>
<?
	ob_end_flush();
?>