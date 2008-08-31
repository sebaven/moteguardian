	<?
class Listado {
	var $id;
	var $debug;
	var $formato_fecha;
	var $db = "";
	var $path_image = "";
	var $ex_pasaget = array("orden");
	var $alistado = array();
	var $javascript = array();
	var $estilo;
	var $estilo_titulo = "";
	var $titulo_general = "";
	var $escapear_html = true;
	var $seleccionar_filas = true;
	var $seleccionar_js = '';
	var $mensaje = "No se hallaron datos para su b&uacute;squeda.";
	var $orden = "orden";		
	var $_conexion = 'seekdispatch';		
	//Propiedades referentes a la muestra del total de registros
	var $mostrar_total = false;
	var $total_registros = 0;
	var $mensaje_total = 'Total de Registros: ';
		
	function Listado($valor_debug = 0)
	{
		$this->db = ConnectionManager::getConnection($this->_conexion);
		$this->formato_fecha = "y-m-d";
		$this->debug = $valor_debug;
		$this->db->debug = $this->debug;
		$this->path_image = "imagenes/listado/";
		$this->estilo = "lista";
		$this->javascript["popup"] = " \n
			<script language=\"JavaScript\"> \n
				function popup_class_listado(nombre,pagina,x,y){ \n
					eval (\"newwin_\" + nombre + \" = window.open('\"+pagina+\"','\"+nombre+\"','height='+y+',top=' + (screen.height-y)/2 + ',width='+x+',left=' + (screen.width - x)/2 + ',toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');\");\n
					eval (\"newwin_\" + nombre + \" . focus(); \");\n
					return false;\n
				}\n
			</script>\n";
	}
        
	function set_ex_pasaget($ex_pasaget){
		$this->ex_pasaget += $ex_pasaget;
	}
        
	function set_estilo($estilo){
		$this->estilo=$estilo;
	}
        
	function set_estilo_titulo($estilo){
		$this->estilo_titulo=$estilo;
	}
        
	function set_titulo($titulo){
    	$this->titulo_general=$titulo;
	}
        
	function set_formato_fecha($formato_fecha){
		$this->formato_fecha=$formato_fecha;
	}

	function set_seleccionar_filas($seleccionarfilas) {
		$this->seleccionar_filas = $seleccionarfilas;
	}

	function pasa_fecha($fecha,$formato_salida){
    	list( $f1, $f2, $f3 ) = split( '[/.-]', $this->formato_fecha);
		list( $$f1, $$f2, $$f3 ) = split( '[/.-]', "$fecha" );
		list( $fs1, $fs2, $fs3 ) = split( '[/.-]', $formato_salida);
		return $$fs1."/".$$fs2."/".$$fs3 ;
	}
        
	function filtrado_sql($str){
		$filtrado = array("'", ";", "\"");
		return str_replace($filtrado, "",$str);
	}
        
	function numregistros(){
		return $this->alistado["numregistros"];
	}
        
	function datos($sql,$datos,$columnas,$cfilas,$maxpag,$order_default,$orden_tipo) {
		
		
		
		// Si no hay ninguna consulta imprimir el mensaje
		if(!$sql){
			$this->alistado["mensaje"] = '<br/><div align="center"><i>'.$this->mensaje.'</i></div><br/>';					
			return;
		}
						
		$offset = $datos["offset".$this->id];
		$orden = $datos[$this->orden];

		if($datos){		
			foreach( $datos as $param => $valor ) {     	               	
				if( !(in_array($param,$this->ex_pasaget)) ){
                   	// Para encodear el referer
					if($param == 'referer') $valor = urlencode($valor);
					// AGREGADO
					if(!is_array($valor)){
						$valor = urlencode($valor);
						$auxarr_ord[] = "$param=" . $valor;						
					}else{
						$auxarr_ord[] = urlencode_array($valor, $param);					
					}					
				}
			}			
		}
                
		if (count($auxarr_ord)>0){
			$pasa_get_ord = "&amp;" . join("&amp;", $auxarr_ord);
		}else{
			$pasa_get_ord="";
		}
		
		if (count ($awhere)>0){
			$where="where ".join(" AND ",$awhere);
		}else{
			$where="";
		}

		//armo la cadena sql de ordenamiento.
		if($orden =="") $orden = $order_default;
		
		$ordensql = ereg_replace(",", "$orden_tipo,", $orden )." ".$orden_tipo;
		$ordensql = $this->filtrado_sql($ordensql);
			
		$sql = "$sql $where "; //sql sin order

		$numrows = $this->pag_numrows($sql);

		$this->alistado["numregistros"] = $numrows;
		
		if ($numrows ==0){
			$this->alistado["mensaje"] = '<br /><div align="center"><i>' . $this->mensaje . '</i></div><br/>';
		}else{
			$offset = $this->pag_check_offset($offset,$numrows);
			$rs = $this->pag_rs("$sql ORDER BY $ordensql",$cfilas,$offset);
			
            //--------------- thead ------------------
			$thead="";
			if($orden_tipo=="desc"){$tipoflecha="d";}else{$tipoflecha="a";}
				foreach($columnas as $valor){
					$a1="";$a2="";$img="";
					if(isset($this->estilo_titulo) && $this->estilo_titulo) $thead .="<th class='$this->estilo_titulo'>";
					else $thead .="<th>";
					$foto_orden = $tipoflecha."flechanegraorden.png";					
					if (($valor["fijo"]==false) && ($valor['orden'] != $orden) ){
						$a1 ="<a href='$PHP_SELF?" . $this->orden . "=" . $valor['orden'] ."$pasa_get_ord' class='accion' title='Ordenar por ".$valor["title"].".'>";
						$a2 ="</a>";
						$foto_orden=$tipoflecha."flechaorden.png";
					}
					if($valor["foto_orden"]){
						$img ="<img src='".$this->path_image."$foto_orden' alt='Ordenar por ".$valor["nombre"].".' border='0' align='absmiddle'>";
					}
					$thead .="$a1".$img.$valor["nombre"]."$a2</th>";
				}
				$titulo ="<tr><th class='titulo_tabla' colspan='".count($columnas)."'>$this->titulo_general</th></tr>";
				$thead = "<thead>$titulo<tr>$thead</tr></thead>";
				$this->alistado["thead"] = $thead;
                        
				//---------------- tbody -----------------
				$tbody="";
				$count=0;
				$hay_un_popup = false;

				while ( ($row = $this->db->fetch_array($rs)) && ($count<$cfilas) ) {                        	
					if ($count%2==0){$class_tr="fila1";}else{$class_tr="fila2";}
					$count++;
					//Seleccionar filas
					if ($this->seleccionar_filas) {
						$class_tr .= 'dsel';
						$tbody .= "<tr class='$class_tr' onmouseover=\"javascript:mouseover(this);\" onmouseout=\"javascript:mouseout(this);\" >\n";
					}
					else {
						$tbody .= "<tr class='$class_tr'>\n";
					}
								
					foreach($columnas as $valor){
                    	$dato="";$a1="";$a2="";$title="";$img="";$dato_align="";$dato_href_parametro="";$dato_width="";$dato_popup="";
                        	if($valor["dato_align"]) $dato_align="align='".$valor["dato_align"]."'";
							if($valor["dato_width"]) $dato_width="width='".$valor["dato_width"]."'";                                        
							if ($valor["datos"]){
								foreach($valor["datos"] as $campo_dato){
									if($valor["formato_fecha"]){
										$dato[] = $this->pasa_fecha($row[$campo_dato],$valor["formato_fecha"]);
									}else{
										$dato[] = $row[$campo_dato];
									}
								}
								$dato= join($valor["datos_delimitador"],$dato);
							}
							if ($valor["dato_title"]){
								$title = "title='".$valor["dato_title"]."'";
							}
							if ($valor["dato_href_parametro"]){
								$dato_href_parametro = $valor["dato_href_parametro"]."=".$row[$valor["dato_href_parametro"]];
							}
							// Las funciones de javascript tienen la forma: funcion(param1, param2, ..., paramN)
							// function(param1, param2)
							if ($valor["dato_onclick"]){											
								// $reg[1] = lista de paramtros
								ereg("\((.*)\)", $valor["dato_onclick"], $regs);
								$params = explode(',', $regs[1]);
											
								// $reg[1] = nombre funcion sin los parentesis
								ereg("(.*)\((.*)\)", $valor["dato_onclick"], $regs);
								$nombre_funcion = $regs[1];

								//Cambiar los paramtros por los valores
								$parametros = array();
								foreach ($params as $param){
									$parametros[] = "'" . $row[trim($param)] . "'";
								}
											
								// Armar la funcion con los parametros
								$function = $nombre_funcion . '(' . implode(',', $parametros) . ')';
								$dato_onclick = 'onclick="' . $function . '"';
							} 
							else {
								$dato_onclick = '';
							}
										
                            if ($valor["dato_href"]){
                            	if (isset($valor["dato_popup"])){
									if($hay_un_popup==false){
										print $this->javascript["popup"];
										$hay_un_popup=true;
									}
                                    $dato_popup = "onclick='javascript:popup_class_listado(\"".$valor["dato_popup"]["nombre"]."\",\"".$valor["dato_href"].$dato_href_parametro."\",".$valor["dato_popup"]["x"].",".$valor["dato_popup"]["y"].");return false' target='_blank' ";
								}
								$a1="<a href='".$valor["dato_href"]."$dato_href_parametro' $title $dato_popup $dato_onclick >";
								$a2="</a>";
							}
                                        
                                        if($valor["dato_foto_variable"]){                                        	
                                        	if(isset($row[$valor['dato_foto_variable']])) {
                                        		if($row[$valor['dato_foto_variable']]==TRUE_){                                        			
                                        			$img ="<img src='imagenes/listado/exitoTarea/true.png"."' border='0' align='absmiddle'>";
                                        		} else if($row[$valor['dato_foto_variable']]==FALSE_){
                                        			$img ="<img src='imagenes/listado/exitoTarea/false.png' border='0' align='absmiddle'>";	
                                        		} 
                                        	} else {                                        		
                                                $img ="<img src='imagenes/listado/exitoTarea/null.png' border='0' align='absmiddle'>";
                                        	}
                                        }
                                        
							if($valor["dato_foto"]) {
								$img ="<img src='".$valor['dato_foto']."' border='0' align='absmiddle'>";
							}
                                        
							if($valor["dato_foto_de_consulta"]) {
								$img = $row[$valor['dato_foto_de_consulta']];
							}
                                        
                                        if($valor['dato_boolean']) {
                                        	if(isset($row[$valor['dato_boolean']]))
                                        		if($row[$valor['dato_boolean']]==TRUE_) $img ="<img src='imagenes/listado/exitoTarea/false.png"."' border='0' align='absmiddle'>";
                                        		else if($row[$valor['dato_boolean']]==FALSE_) $img ="<img src='imagenes/listado/exitoTarea/true.png' border='0' align='absmiddle'>";	
                                        	else $img ="<img src='imagenes/listado/exitoTarea/null.png' border='0' align='absmiddle'>";                                        	
                                        }
							if($valor["dato_checkbox"]) {
									$dato = "<input type='checkbox' name='".$valor["dato_checkbox"]."_fila_".$row[$valor["dato_checkbox"]]."' />";
									$this->escapear_html = false; 
							}
                                        
							$dato_click = '';
							if (($this->seleccionar_filas) && (empty($valor["dato_href"])) && ($this->seleccionar_js) ) {
								$dato_click = " onclick=\"$this->seleccionar_js('$row[id]')\" ";
							}
							
							if($this->escapear_html) {
								$tbody .= "<td $dato_align $dato_width $dato_click >$a1$img".htmlentities($dato)."$a2</td>\n";
							}
							else {
								$tbody .= "<td $dato_align $dato_width $dato_click >$a1$img".$dato."$a2</td>\n";
							}
										
						}
						$tbody.="</tr>\n";
					}                
                    $this->alistado["tbody"] = "<tbody>$tbody</tbody>";
						
                    //----------------- tfoot ----------------
					if ($numrows >= $cfilas) {						
						// 	Incluir paginado
						$tfoot= "<tfoot align='center'><tr><td colspan='14' style='height:25px;'>".$this->navegador($datos, $offset, $numrows, $cfilas, $maxpag)."</td></tr></tfoot>";														
					}
					else {
						// Foot sin paginado
						$tfoot= "<tfoot align='center'><tr><td colspan='14' height='1px'></td></tr></tfoot>";
					}
					
					// Asignar el tfoot
					$this->alistado["tfoot"] = $tfoot;
               }
	}
	
	function imprimir_listado(){
		// Script para cambiar de color las filas
		// Los estilos que usa son
		//		para las deseleccionadas: fila1dsel, fila2dsel
		//		para las seleccionadas: fila1sel, filadsel
		echo "<script type=\"text/javascript\">		
		function mouseover(cual)\n
		{\n
			if (cual.className == 'fila1dsel') cual.className = 'fila1sel';\n
			if (cual.className == 'fila2dsel') cual.className = 'fila2sel';\n
		}\n
		\n
		function mouseout(cual)\n
		{\n
			if (cual.className == 'fila1sel') cual.className = 'fila1dsel';\n
			if (cual.className == 'fila2sel') cual.className = 'fila2dsel';\n
		}\n					
		</script>\n
		";			
		if($this->numregistros()){
			$s = "<table cellspacing=0 class='".$this->estilo."'>".$this->alistado["thead"].$this->alistado["tfoot"].$this->alistado["tbody"]."</table>";
			
			if ($this->mostrar_total){
				$s .= "<div style='text-align:right;'><strong>". $this->mensaje_total . number_format($this->total_registros, 0, ',', '.') .'</strong></div>';
			}
			
			return $s;
		}
        else{
			return $this->alistado["mensaje"];
		}
	}
       
	function listado_numregistros(){
		return $this->alistado["numregistros"];
	}
    
	function pag_numrows($sql){
		$union = stristr($sql, "UNION");
				
		if($union){
			return $this->db->numero_filas($this->db->leer($sql));        			
		}else{
			$sql_numrows = "SELECT ";
			// Buscar si hay algun DISTINCT en la consulta
			$distinct = stristr($sql, "DISTINCT");
	
			if ($distinct){
			// Buscar el nombre del campo, el mismo termina en la coma o si hay un AS antes que
			// la coma
				if (stristr($distinct, "AS")){
					$pos_as = strpos(strtoupper($distinct), "AS");
				}					
				$pos_comma = strpos($distinct, ",");
				$pos_from = strpos(strtoupper($distinct), "FROM");
						
						// Cargar las posiciones, que no estan en FALSE, en el array
						if ($pos_as)
						{
							$positions[] = $pos_as;
						}
						if ($pos_comma)
						{
							$positions[] = $pos_comma;
						}
						if ($pos_from)
						{
							$positions[] = $pos_from;
						}
						
						// Tomar la menor posicion entre pos_as, pos_comma y pos_from
						$pos = min($positions);
										
						$field = substr($distinct, strlen("DISTINCT"), $pos - strlen("DISTINCT"));
						$count = "Count(DISTINCT " . $field . ")";						
					}
					else
					{
						$count = "Count(*)";
					}
	
	
	                // Buscar si hay algun GROUP en la consulta
					$group = stristr($sql, "GROUP BY");
					if ($group)
					{
						$pos = strpos($sql, 'GROUP BY');
						$fields = substr($sql, $pos + strlen('GROUP BY'));					
						
						$pos = strpos($sql, 'GROUP BY');
						$sql = substr($sql, 0, $pos);
						$count  = "Count(DISTINCT $fields)";
					}
	
					$sql_numrows .= $count . " AS numrows " . stristr($sql, "FROM");
					
	                $numresults = $this->db->leer($sql_numrows);
	                $numresults = $this->db->fetch_array($numresults);
	                $numrows = $numresults["numrows"];
					$this->total_registros = $numrows;
	
	                return $numrows;
        		}
        }
		
        function pag_check_offset($offset,$numrows){
                //chequeo el $offset
                if (empty($offset) || ($offset < 0) || ($offset > $numrows)) {
                        $offset=0;
                }
                return $offset;
        }
        function pag_rs($sql,$cfilas,$offset){
// FIXME: ahora solo esta para Mysql
                //--- Ejecuciï¿½n de la consulta ---
/*                if ($this->db->motor=='mssql'){
                        $rs = $this->db->leer("SELECT @@version as sql_version");
                        $rs = $this->db->fetch_array($rs);
                        if (strpos($rs["sql_version"],"6.50")===false){
                                $sql = "SELECT top $cfilas ".substr($sql,6,strlen($sql));
                        }
						
						$rs = $this->db->leer($sql);
                        mssql_data_seek($rs,$offset);
                }*/
                //if ($this->db->motor=='mysql'){
                        $sql = "$sql LIMIT $offset,$cfilas";
                        //die(printr($sql));                        
                        $rs = $this->db->leer($sql);

                //}
               /* if ($this->db->motor=='pg'){
                        $sql = "$sql LIMIT $cfilas OFFSET $offset";
                        $rs = $this->db->leer($sql);
                }*/
                //print $sql;
                return $rs;
        }
        
	function navegador($datos,$offset,$numrows,$cfilas,$maxpag){
		$id = $this->id;        	
		//****************************************** CARGA DEL NAVERGADOR *********************************************************************
        // variables del get que no se van a volver a pasar
		$ex_pasaget = array("offset".$id,"borrar","publicar","despublicar");
			
		if($datos){               
			foreach( $datos as $param => $valor ) {
				if ( !(in_array($param,$ex_pasaget)) ){
					// AGREGADO
					if(!is_array($valor)){
						$valor = urlencode($valor);
						$auxarr_nav[] = "$param=" . $valor;						
					}else{
						$auxarr_nav[] = urlencode_array($valor, $param);					
					}										
				}
			}
		}
		
		if(count($auxarr_nav)>0){
			$pasa_get ="&amp;".join("&amp;",$auxarr_nav);
		}else{
			$pasa_get="";
		}
						
        //---Calculos de paguinado---
        $paginaactual = ($offset/$cfilas)+1;
        $intermaxpag = intval(($paginaactual-1)/$maxpag);
        $pages = intval($numrows/$cfilas);
        if ($numrows%$cfilas) {
        	$pages++;
		}
		$total_pages = $pages; //total de paginas
		
		//Logica de maximo de paginas en el navegador
		if ($pages>($intermaxpag*$maxpag)){
			//si el numero total de pagias es mayor a el numero de inervalos por el maximo de paginas a                 // mostrar..
			if ($pages>($intermaxpag*$maxpag)+$maxpag){    //lo mismo que el anterior pero este sirbe para el ultimo intervlo.
				$pages=($intermaxpag*$maxpag)+$maxpag;     //modifica el valor de las paginas a mostrar segun el limite de paginas
			}
			$init = ($intermaxpag * $maxpag)+1;                //modifica el valor de donde va a empesar a mostrar los paginas
		}
		$str_nav = "<div class=\"paginado\">";

		// pfagalde: Para que en el caso de que la consulta traiga mas resultados	
		//			pero solo querramos mostrar una pagina
                
		if (($total_pages>1) && ($maxpag >1)){
   	    	//link anterior
			if ($offset>0 && ($offset <= $numrows)) {                                         // previene que no aparezca el link de pagina anterior
				$prevoffset=$offset-$cfilas;
				$str_nav = $str_nav."<a  class=\"prevnext\" href=\"$PHP_SELF?offset$id=0$pasa_get\">« Primera</a> &nbsp; \n";
				if ($intermaxpag>=1){
					$str_nav = $str_nav."<a  class=\"prevnext\" href=\"$PHP_SELF?offset$id=".((($maxpag*$cfilas)*($intermaxpag))-($maxpag*$cfilas))."$pasa_get\"><img src='".$this->path_image."boton_retro.gif' alt='Retroceder $maxpag p&aacute;ginas' style='border:0px; text-align:center;'></a> &nbsp;\n";
				}
				$str_nav = $str_nav."<a class=\"prevnext\" href=\"$PHP_SELF?offset$id=$prevoffset$pasa_get\">« Anterior</a> &nbsp; \n";
			}

			//arma del navegador
			for ($i=$init;$i<=($pages);$i++) {
				if ((($offset/$cfilas)+1)==$i){
				}
				$newoffset = $cfilas*($i-1);
				$str_nav = $str_nav."<a class='paginado' href=\"$PHP_SELF?offset$id=$newoffset$pasa_get\">".((($offset/$cfilas)+1==$i) ? "<strong class='actual'>$i</strong>" : "$i")."</a> &nbsp; \n";
			}

			// link siguiente
			if (!(($offset/$cfilas)==$pages) ) {
			//para que la ultima pagina no tenga siguiente
				$newoffset = $offset+$cfilas;
				if((($numrows - $offset) > $cfilas)  && ($offset < $numrows)){
					$str_nav = $str_nav."<a class=\"prevnext\" href=\"$PHP_SELF?offset$id=$newoffset$pasa_get\">Siguiente »</a> &nbsp;\n";
					if ((((($intermaxpag+1)*$maxpag)*$cfilas)==($pages*$cfilas))&&($total_pages<>$pages)){
						$str_nav = $str_nav."<a class=\"prevnext\" href=\"$PHP_SELF?offset$id=".((($intermaxpag+1)*$maxpag)*$cfilas)."$pasa_get\"><img src='".$this->path_image."boton_avance.gif' alt='Adelantar $maxpag P&aacute;ginas' style='border:0px; text-align:center;'></a> &nbsp;\n";
					}
					$str_nav = $str_nav."<a class=\"prevnext \" href=\"$PHP_SELF?offset$id=".(($total_pages*$cfilas)-$cfilas)."$pasa_get\">Ultima »</a>\n";
				}			                      
			}       
			$str_nav = $str_nav."</div>";   
		    return $str_nav;
		}
	}
}
?>
