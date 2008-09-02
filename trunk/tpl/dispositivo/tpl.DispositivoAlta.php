<h2 align="right"><?= $accion_dispositivos ?> de Usuarios</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="dispositivo_new">
	<input type="hidden" name="accion" value="dispositivo_new" />
	<fieldset>
		<legend><?= $keys['usuario.adm.datos'] ?></legend><br>
		<br>
		<table width="70%" align="center" border="0">
          <tr>
            <td align="right"><p>Tipo</p></td>
            <td><select name="id_tipo" id="id_tipo">
                <? ComboControl::Display($options_tipo, $id_tipo)?>
              </select>            </td>
            <td align="right"><p>Estado</p></td>
            <td><input type="text" name="estado" size="25" value="<?= htmlentities($estado) ?>"/></td>
          </tr>
          <tr>
            <td align="right"><p>C&oacute;digo</p></td>
            <td><input type="text" name="codigo" value="<?= htmlentities($codigo) ?>" size="25"/></td>
            <td align="right"><p>Descripci&oacute;n</p></td>
            <td><input type="text" name="descripcion" value="<?= htmlentities($descripcion) ?>" size="25"/></td>
          </tr>
          <tr>
            <td align="right"><p>Sala</p></td>
            <td><select name="id_sala" id="id_sala">
                <? ComboControl::Display($options_sala, $id_sala)?>
              </select>            </td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
		<br>
			<div align="center">
				<span class="botonGrad"><input type="submit"  name ="btnProcesar" value="Aceptar" class="boton" /></span>
				<span class="botonGrad"><input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=usuario_adm'; return false;" /></span>
			</div>
		<br>
	</fieldset>
</form>
<br/>
