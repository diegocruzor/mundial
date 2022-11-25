<html>
<?php
	session_start();
	//

//Este auxiliar es para actualizar los puntos en los participantes de acuerdo 
//a sus aciertos en los partidos de acuerdo al número de partido asignado en
//la variable $partido


	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	$hoy = date("Y-m-d");
	$cont = 0;
	$puntos = 0;
	$partido = 50;
	//
	$xcon = mysql_connect("localhost","root");
	mysql_select_db("mundial",$xcon);
	//
	$sql1 = "SELECT * FROM participantes2 WHERE Idparticipante = '".$_SESSION['i']."'";;
	$r1 = mysql_query($sql1) or die("No se encontr? el usuario. Error: ".mysqli_error($xcon));
	if ($datos1 = mysql_fetch_array($r1)){}
	else 	header('Location: index.php');
	//
						//ejecutar el UPDATE en equipos
						//$sql4 = "UPDATE equipos 
						//		SET PJ = ".$PJ.", 
						//			PG = ".$PG.", 
						//			PE = ".$PE.", 
						//			PP = ".$PP.", 
						//			GF = ".$GF.", 
						//			GC = ".$GC.", 
						//			DI = '".$DI."', 
						//			PTS = ".$PTS." 
						//		WHERE Nombre = '".$datos1["Equipo_1"]."'";
						//$r4 = mysql_query($sql4) or die ("No se puede ingresar el resultado. Error: ".mysqli_error($xcon));
					
				
					//Consolidar puntos para cada participante
					//Seleccionar todos los participantes
					$sql8 = "SELECT * FROM participantes";
					$r8 = mysql_query($sql8) or die("No se pueden seleccionar los participantes. Error: ".mysql_error());
					while ($participantes = mysql_fetch_array($r8))
					{	
						//Seleccionar el partido al que se le ingres? el resultado final
						$sql9 = "SELECT * FROM partidos WHERE NoPartido = ".$partido;
						$r9 = mysql_query($sql9) or die("No se puede seleccionar el partido. Error: ".mysql_error());
						if ($partidos = mysql_fetch_array($r9))
						{
							//Obtener el resultado ingresado por el participante en el partido
							$sql10 = "SELECT * FROM resultados WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND NoPartido = ".$partido;
							$r10 = mysql_query($sql10) or die("No se puede seleccionar el resultado. Error: ".mysql_error());
							if ($resultados = mysql_fetch_array($r10))
							{
								$puntosGanados = 0;
								//Comparar resultados e ingresar datos a la tabla puntos por partido
								if ($partidos["Resultado_1"] == $resultados["Resultado_1"] && $partidos["Resultado_2"] == $resultados["Resultado_2"])
								{
									$puntosGanados += 2;
								}else if ($partidos["Ganador"] == $resultados["Ganador"])
								{
									$puntosGanados++;	
								}
								//obtener id del ?ltimo registro en puntosporpartido
								$sql11 = "SELECT COUNT(*) FROM puntosporpartido";
								$r11 = mysql_query($sql11)or die("No se pueden contar los registros. Error: ".mysql_error());
								if ($idppp = mysql_fetch_row($r11)) $cont = $idppp[0] + 1;
								
								//ingresar datos a la tabla puntosporpartido
								$sql12 = "INSERT INTO puntosporpartido VALUES(".$cont.", '".$participantes["IdParticipante"]."',".$partidos["NoPartido"].", ".$puntosGanados.")";
								$r12 = mysql_query($sql12) or die("No se puede insertar el registro en la tabla puntosporpartido. Error: ".mysql_error());
								
								//Sumar los puntos obtenidos y modificarlos en la tabla puntajes
								$sql13 = "SELECT * FROM puntajes WHERE IdParticipante = '".$participantes["IdParticipante"]."'";
								$r13 = mysql_query($sql13) or die("No se pueden seleccionar los puntajes. Error: ".mysql_error());
								if ($puntajes = mysql_fetch_array($r13)) $puntos = $puntajes["Puntos"] + $puntosGanados; 
								
								//Actualizar los puntos totales
								$sql14 = "UPDATE puntajes SET Puntos = ".$puntos." WHERE IdParticipante = '".$participantes["IdParticipante"]."'";
								$r14 = mysql_query($sql14) or die("No se pueden actualizar los puntajes. Error: ".mysql_error());
								//
								$puntos = 0;
							}//Fin if resultados
						}//Fin if partido con resultado modificado
					}//Fin while participantes
    date_default_timezone_set('UTC');
    //Imprimimos la fecha actual dandole un formato
?>
	<head>
		<title>Gran Polla FONDOR</title>
		<script language="JavaScript" src="datos/date.js"></script> 
	</head>

	<body onLoad="Reloj()">
    	<font face="Verdana, Geneva, sans-serif">
		<center>
		<h1>Gran Polla FONDOR</h1>
		<form name="form_reloj"> 
			<input type="text" name="reloj" size="70" style="background-color : White; color : Black; font-family : Arial; font-size : 10pt; text-align : center;" disabled> 
		</form>
        Por favor ingresar la informaci&oacute;n solicitada:<br><br>
<?php
	$sql1 = "SELECT * FROM partidos WHERE Fecha = '".$hoy."'";
	$r1 = mysql_query($sql1);
	while ($datos1 = mysql_fetch_array($r1))
	{
?>
		<form name="<?php echo $datos1["NoPartido"]; ?>" method="post" action="ingresoresultados.php?p=<?php echo $datos1["NoPartido"]; ?>">
        	<table border="0">
				<tr><td colspan="3" align="center"><?php echo $datos1["Lugar"]." - ".$datos1["Hora"]; ?></td></tr>
				<tr>
					<td style="font-family : Arial; font-size : 8pt; text-align : center;" width="87px"><?php echo $datos1["Equipo_1"]; ?></td>
					<td>&nbsp;</td>
					<td style="font-family : Arial; font-size : 8pt; text-align : center;" width="87px"><?php echo $datos1["Equipo_2"]; ?></td>
				</tr>
                <tr>
<?php
		$sql2 = "SELECT * FROM equipos WHERE Nombre = '".$datos1["Equipo_1"]."'";
		$r2 = mysql_query($sql2);
		if ($datos2 = mysql_fetch_array($r2))
		{
			echo "<td><img src='".$datos2["Bandera"]."' width='100%'></td>";
		}
?>
           			<td><input name="<?php echo "R1-".$datos1["NoPartido"]; ?>" type="text" id="<?php echo "R1-".$datos1["NoPartido"]; ?>" size="2" maxlength="2" style="text-align : center;" value=<?php if($datos1["Estado"] == "Jugado") echo "'".$datos1["Resultado_1"]."' Disabled"; else echo "'-'"; ?>> - <input name="<?php echo "R2-".$datos1["NoPartido"]; ?>" type="text" id="<?php echo "R2-".$datos1["NoPartido"]; ?>" size="2" maxlength="2" style="text-align : center;" value=<?php if($datos1["Estado"] == "Jugado") echo "'".$datos1["Resultado_2"]."' Disabled"; else echo "'-'"; ?>></td>
<?php
		$sql3 = "SELECT * FROM equipos WHERE Nombre = '".$datos1["Equipo_2"]."'";
		$r3 = mysql_query($sql3);
		if ($datos3 = mysql_fetch_array($r3))
		{
			echo "<td><img src='".$datos3["Bandera"]."' width='100%'></td>";
		}
?>				
				</tr>
				<tr><td colspan="3"<?php if($datos1["Estado"] != "Jugado") echo "align='center'><input type='submit' name='guardar' value='Guardar'></td></tr>
                							<tr><td colspan='3'>&nbsp;</td></tr>"; else echo "<tr><td colspan='3'>&nbsp;</td></tr>"; ?> 
            </table>
        </form>
<?php
	}//Fin while $sql1
	mysql_close($xcon);
?>
		<form name="volver" method="post" action="presentacion.php"> 
        	<input type="submit" name="volver" value="Volver"> 
		</form>
			____________________________________________
			<br><font face="Trebuchet MS, Arial, Helvetica, sans-serif" size="2">Realizado por Diego Cruz &copy 2022</font>
			</center>
    	</font>
    </body>
</html>	