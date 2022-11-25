<html>
<?php
	session_start();
	//
//Este auxiliar se encarga de actualizar puntajes para cada participante despu�s de comparar
//las cuatro primeras posiciones de los equipos en el torneo
	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	$hoy = date("Y-m-d");
	$cont = 0;
	$puntos = 0;
	//
	$xcon = mysqli_connect('localhost', 'admin', '123456', 'mundial');
	if (!$xcon) {
    die('Error de Conexi�n (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
	}
	//
	$sql1 = "SELECT * FROM participantes2 WHERE Idparticipante = '".$_SESSION['i']."'";;
	$r1 = mysql_query($sql1) or die("No se encontr? el usuario. Error: ".mysqli_error($xcon));
	if ($datos1 = mysql_fetch_array($r1)){}
	else 	header('Location: index.php');
	//
	//Consolidar puntos para cada participante en las cuatro primeras posiciones de los equipos en el torneo
	//Seleccionar todos los participantes
	$sql8 = "SELECT * FROM participantes";
	$r8 = mysql_query($sql8) or die("No se pueden seleccionar los participantes. Error: ".mysql_error());
	while ($participantes = mysql_fetch_array($r8))
	{	
		for ($posicion = 1;$posicion <= 4;$posicion++)
		{
			//Seleccionar el equipo seg�n la posici�n lograda
			$sql9 = "SELECT * FROM equipos_octavos WHERE Posicion = ".$posicion;
			$r9 = mysql_query($sql9) or die("No se puede seleccionar equipo en equipos_octavos. Error: ".mysql_error());
			if ($equipos_octavos = mysql_fetch_array($r9))
			{
				//Seleccionar el equipo seg�n la posici�n pronosticada por el participante
				$sql10 = "SELECT * FROM equipos_octavos_pronostico WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Posicion = ".$posicion;
				$r10 = mysql_query($sql10) or die("No se puede seleccionar equipo en equipos_octavos_pronostico. Error: ".mysql_error());
				if ($equipos_octavos_pronostico = mysql_fetch_array($r10))
				{
					//Si coincide el equipo pron�stico con la posici�n real del equipo entre los cuatro primeros
					if ($equipos_octavos["Nombre"] == $equipos_octavos_pronostico["Nombre"])
					{
						//Actualizar puntos en tabla equipos_octavos_pronostico al participante
						$sql12 = "UPDATE equipos_octavos_pronostico 
								  SET Puntos2 = 2 
								  WHERE IdParticipante = '".$participantes["IdParticipante"]."' 
								  	AND Posicion = ".$posicion;
						echo $sql12."<br>";
						$r12 = mysql_query($sql12) or die("No se puede acualizar equipos_octavos_pronostico. Error: ".mysql_error());
				
						//Actualizar la tabla puntajes por los puntos obtenidos al acertar la posici�n 
						$sql13 = "SELECT * FROM puntajes WHERE IdParticipante = '".$participantes["IdParticipante"]."'";
						$r13 = mysql_query($sql13) or die("No se puede seleccionar puntajes. Error: ".mysql_error());
						if ($puntajes = mysql_fetch_array($r13))
						{
							$puntos = $puntajes["Puntos"];
							$puntos += 2;
							$sql14 = "UPDATE puntajes 
									  SET Puntos = ".$puntos."
									  WHERE IdParticipante = '".$participantes["IdParticipante"]."'";
							echo $sql14."<br>";
							$r14 = mysql_query($sql14);
						}//Fin if puntajes actualizaci�n puntuaci�n por participante si acert� el equipo en la posici�n
					}//Fin if comparaci�n de los nombres de equipos en las dos tablas
				}//Fin if equipos_octavos_pronostico
			}//Fin if equipos_octavos
		}//Fin for posici�n
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
        Modificaci�n realizada.<br><br>
<?php
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