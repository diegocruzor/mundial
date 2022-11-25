<html>
<?php
	session_start();
	//

//Este auxiliar se encarga de actualizar los cuatro equipos en los dos partidos 
//de la final en el participante

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
	$sql1 = "SELECT * FROM participantes WHERE Idparticipante = '".$_SESSION['i']."'";;
	$r1 = mysql_query($sql1) or die("No se encontr? el usuario. Error: ".mysqli_error($xcon));
	if ($datos1 = mysql_fetch_array($r1)){}
	else 	header('Location: index.php');
	//
	//Consolidar puntos para cada participante
	//Seleccionar todos los participantes
	$sql8 = "SELECT * FROM participantes";
	$r8 = mysql_query($sql8) or die("No se pueden seleccionar los participantes. Error: ".mysql_error());
	while ($participantes = mysql_fetch_array($r8))
	{	
		$partido = 63;
		//Seleccionar el partido de la semifinal en los resultados del participante
		$sql9 = "SELECT * FROM resultados WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND NoPartido = ".$partido;
		$r9 = mysql_query($sql9) or die("No se puede seleccionar el partido. Error: ".mysql_error());
		if ($resultados = mysql_fetch_array($r9))
		{
			if ($resultados["Equipo_1"] == $resultados["Ganador"])
			{
				//Actualizar la posici�n 3 del equipo en el participante, en equipos octavos pron�stico
				$sql7 = "UPDATE equipos_octavos_pronostico set Posicion = 3 WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Nombre = '".$resultados["Equipo_1"]."'";
				$r7 = mysql_query($sql7);
				
				//Actualizar la posici�n 4 del equipo en el participante, en equipos octavos pron�stico
				$sql6 = "UPDATE equipos_octavos_pronostico set Posicion = 4 WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Nombre = '".$resultados["Equipo_2"]."'";
				$r6 = mysql_query($sql6);
			}
			elseif ($resultados["Equipo_2"] == $resultados["Ganador"])
			{
				//Actualizar la posici�n 3 del equipo en el participante, en equipos octavos pron�stico
				$sql7 = "UPDATE equipos_octavos_pronostico set Posicion = 3 WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Nombre = '".$resultados["Equipo_2"]."'";
				$r7 = mysql_query($sql7);
				
				//Actualizar la posici�n 4 del equipo en el participante, en equipos octavos pron�stico
				$sql6 = "UPDATE equipos_octavos_pronostico set Posicion = 4 WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Nombre = '".$resultados["Equipo_1"]."'";
				$r6 = mysql_query($sql6);
			}
		}
		
		$partido++;
		//Seleccionar el partido de la final en los resultados del participante
		$sql9 = "SELECT * FROM resultados WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND NoPartido = ".$partido;
		$r9 = mysql_query($sql9) or die("No se puede seleccionar el partido. Error: ".mysql_error());
		if ($resultados = mysql_fetch_array($r9))
		{
			if ($resultados["Equipo_1"] == $resultados["Ganador"])
			{
				//Actualizar la posici�n 3 del equipo en el participante, en equipos octavos pron�stico
				$sql7 = "UPDATE equipos_octavos_pronostico set Posicion = 1 WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Nombre = '".$resultados["Equipo_1"]."'";
				$r7 = mysql_query($sql7);
				
				//Actualizar la posici�n 4 del equipo en el participante, en equipos octavos pron�stico
				$sql6 = "UPDATE equipos_octavos_pronostico set Posicion = 2 WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Nombre = '".$resultados["Equipo_2"]."'";
				$r6 = mysql_query($sql6);
			}
			elseif ($resultados["Equipo_2"] == $resultados["Ganador"])
			{
				//Actualizar la posici�n 3 del equipo en el participante, en equipos octavos pron�stico
				$sql7 = "UPDATE equipos_octavos_pronostico set Posicion = 1 WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Nombre = '".$resultados["Equipo_2"]."'";
				$r7 = mysql_query($sql7);
				
				//Actualizar la posici�n 4 del equipo en el participante, en equipos octavos pron�stico
				$sql6 = "UPDATE equipos_octavos_pronostico set Posicion = 2 WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND Nombre = '".$resultados["Equipo_1"]."'";
				$r6 = mysql_query($sql6);
			}
		}//Fin if resultados
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