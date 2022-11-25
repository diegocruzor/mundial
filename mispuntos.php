<?php
	session_start();
	require_once 'datos/functions.php';
	//
	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	//
	$xcon = conectarBd(); 	
	if (!$xcon) {
    die('Error de Conexión (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
	}
	//
	$sql1 = "SELECT * FROM participantes WHERE Idparticipante = '".$_SESSION['i']."'";;
	$r1 = mysqli_query($xcon, $sql1) or die("No se encontr� el usuario. Error: ".mysqli_error($xcon));
	if ($datos1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)){}
	else 	header('Location: index.php');
	// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('UTC');
?>
<!DOCTYPE html Content-type: text/html; charset=utf-8>
<html lang="es">
<?php 
	headers2();	
	$sql2 = "SELECT * FROM participantes WHERE IdParticipante = '".$_SESSION["i"]."'";
	$r2 = mysqli_query($xcon, $sql2) or die("No se encontr� el usuario. Error: ".mysqli_error($xcon));
	if ($participantes = mysqli_fetch_array($r2, MYSQLI_ASSOC))
	{
		echo "Bienvenido(a): ".$participantes["Nombre"]."<br>a tu pron&oacute;stico de partidos jugados<br><br>";
	}
?>
 		<table border="1" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;">
			<strong><tr>
				<td width="30px" align="center" rowspan="2">N&uacute;mero Partido</td>
				<td align="center" rowspan="2">Grupo</td>
				<td align="center" colspan="3">Resultado Partido</td>
				<td align="center" colspan="3">Mi pron&oacute;stico</td>
				<td align="center" rowspan="2">Ganador</td>
				<td align="center" rowspan="2" >Estado</td>
				<td width="20px" align="center" rowspan="2">Mis puntos obtenidos</td>
			</tr>
            <tr>
				<td align="center">Equipo 1</td>
				<td align="center">R1 - R2</td>
				<td align="center">Equipo 2</td>
				<td align="center">Equipo 1</td>
				<td align="center">R1 - R2</td>
				<td align="center">Equipo 2</td>
			</tr></strong>
 <?php
	$sql3 = "SELECT * FROM partidos ORDER BY NoPartido ASC";
	$r3 = mysqli_query($xcon, $sql3);
	while ($partidos = mysqli_fetch_array($r3, MYSQLI_ASSOC))
	{
		$sql4 = "SELECT * FROM resultados WHERE IdParticipante = '".$_SESSION["i"]."' AND NoPartido = ".$partidos["NoPartido"];
		$r4 = mysqli_query($xcon, $sql4);
		if ($resultados = mysqli_fetch_array($r4, MYSQLI_ASSOC))
		{
			$sql5 = "SELECT * FROM puntosporpartido WHERE IdParticipante = '".$_SESSION["i"]."' AND NoPartido = ".$partidos["NoPartido"];
			$r5 = mysqli_query($xcon, $sql5);
			if ($ppp = mysqli_fetch_array($r5, MYSQLI_ASSOC))
			{
				echo "<tr>
						<td align='center'>".$partidos["NoPartido"]."</td>
						<td align='center'>".$partidos["Grupo"]."</td>
						<td align='center'style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$partidos["Equipo_1"]."<br>";
							$sql6 = "SELECT * FROM equipos WHERE Nombre = '".$partidos["Equipo_1"]."'";
							$r6 = mysqli_query($xcon, $sql6);
							if ($datos3 = mysqli_fetch_array($r6, MYSQLI_ASSOC))
							{
								echo "<img src='".$datos3["Bandera"]."' width='30%'></td>";
							}
				echo "	<td align='center'>".$partidos["Resultado_1"]." - ".$partidos["Resultado_2"]."</td>
						<td align='center'style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$partidos["Equipo_2"]."<br>";
							$sql7 = "SELECT * FROM equipos WHERE Nombre = '".$partidos["Equipo_2"]."'";
							$r7 = mysqli_query($xcon, $sql7);
							if ($datos4 = mysqli_fetch_array($r7, MYSQLI_ASSOC))
							{
								echo "<img src='".$datos4["Bandera"]."' width='30%'></td>";
							}
				echo "	<td align='center'style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$resultados["Equipo_1"]."<br>";
							$sql8 = "SELECT * FROM equipos WHERE Nombre = '".$resultados["Equipo_1"]."'";
							$r8 = mysqli_query($xcon, $sql8);
							if ($datos5 = mysqli_fetch_array($r8, MYSQLI_ASSOC))
							{
								echo "<img src='".$datos5["Bandera"]."' width='30%'></td>";
							}
				echo "	<td align='center'>".$resultados["Resultado_1"]." - ".$resultados["Resultado_2"]."</td>
						<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$resultados["Equipo_2"]."<br>";
							$sql9 = "SELECT * FROM equipos WHERE Nombre = '".$resultados["Equipo_2"]."'";
							$r9 = mysqli_query($xcon, $sql9);
							if ($datos6 = mysqli_fetch_array($r9, MYSQLI_ASSOC))
							{
								echo "<img src='".$datos6["Bandera"]."' width='30%'></td>";
							}
				echo "	<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$partidos["Ganador"]."</td>
						<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:10px;'>".$partidos["Estado"]."</td>
						<td align='center'>".$ppp["Puntos"]."</td>
					  </tr>";

			}//Fin if puntosporpartido
		}//Fin if resultados
	}//Fin while partidos
	//
?>
          </table><br><br>   
 		
        EQUIPOS EN OCTAVOS:<br><br>
        <table border="1" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;">
			<strong><tr>
				<td width="30px" align="center">Orden Equipo</td>
				<td align="center">Equipo Clasificado</td>
				<td align="center">Mi Equipo Pron&oacute;stico</td>
				<td width="20px" align="center">Mis puntos obtenidos</td>
			</tr></strong>
 <?php
	$sql10 = "SELECT * FROM equipos_octavos ORDER BY Id ASC";
	$r10 = mysqli_query($xcon, $sql10);
	while ($equipos_octavos = mysqli_fetch_array($r10, MYSQLI_ASSOC))
	{
		$sql11 = "SELECT * FROM equipos_octavos_pronostico WHERE IdEquipo = '".$equipos_octavos["Id"]."' AND IdParticipante = '".$_SESSION["i"]."'";
		$r11 = mysqli_query($xcon, $sql11);
		if ($equipos_octavos_pronostico = mysqli_fetch_array($r11, MYSQLI_ASSOC))
		{
			echo "<tr>
					<td align='center'>".$equipos_octavos["Id"]."</td>
					<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$equipos_octavos["Nombre"]."<br><img src='".$equipos_octavos["Bandera"]."' width='30%'></td>
					<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$equipos_octavos_pronostico["Nombre"]."<br>";
						$sql12 = "SELECT * FROM equipos WHERE Nombre = '".$equipos_octavos_pronostico["Nombre"]."'";
						$r12 = mysqli_query($xcon, $sql12);
						if ($equipos = mysqli_fetch_array($r12, MYSQLI_ASSOC))
						{
							echo "<img src='".$equipos["Bandera"]."' width='30%'></td>";
						}
			echo "	<td align='center'>".$equipos_octavos_pronostico["Puntos"]."</td>
				  </tr>";
		}//Fin if equipos octavos pronóstico
	}//Fin while equipos octavos
	//
?>		
       	</table><br><br>
  
        EQUIPOS EN LAS CUATRO PRIMERAS POSICIONES:<br><br>
        <table border="1" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;">
			<strong><tr>
				<td width="30px" align="center">Orden Equipo</td>
				<td align="center">Equipo Clasificado</td>
				<td align="center">Mi Equipo Pron&oacute;stico</td>
				<td width="20px" align="center">Mis puntos obtenidos</td>
			</tr></strong>
 <?php
	$sql10 = "SELECT * FROM equipos_octavos WHERE Posicion != 0 ORDER BY Posicion ASC";
	$r10 = mysqli_query($xcon, $sql10);
	while ($equipos_octavos = mysqli_fetch_array($r10, MYSQLI_ASSOC))
	{
		$sql11 = "SELECT * FROM equipos_octavos_pronostico WHERE IdParticipante = '".$_SESSION["i"]."' AND Posicion = '".$equipos_octavos["Posicion"]."' ";
		$r11 = mysqli_query($xcon, $sql11);
		if ($equipos_octavos_pronostico = mysqli_fetch_array($r11, MYSQLI_ASSOC))
		{
			echo "<tr>
					<td align='center'>".$equipos_octavos["Posicion"]."</td>
					<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$equipos_octavos["Nombre"]."<br><img src='".$equipos_octavos["Bandera"]."' width='30%'></td>
					<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;'>".$equipos_octavos_pronostico["Nombre"]."<br>";
						$sql12 = "SELECT * FROM equipos WHERE Nombre = '".$equipos_octavos_pronostico["Nombre"]."'";
						$r12 = mysqli_query($xcon, $sql12);
						if ($equipos = mysqli_fetch_array($r12, MYSQLI_ASSOC))
						{
							echo "<img src='".$equipos["Bandera"]."' width='30%'></td>";
						}
			echo "	<td align='center'>".$equipos_octavos_pronostico["Puntos2"]."</td>
				  </tr>";
		}//Fin if equipos octavos pronóstico
	}//Fin while equipos octavos
	//
?>		
       	</table><br>
		<table border="1" style="font-family:'Comic Sans MS', cursive; font-size:24px;">
            <tr>
            	<td class="bg-danger" align="center" width="150px">
<?php
					$sql10 = "SELECT * FROM puntajes WHERE IdParticipante = '".$_SESSION["i"]."'";
					$r10 = mysqli_query($xcon, $sql10);
					if ($puntajes = mysqli_fetch_array($r10, MYSQLI_ASSOC)) echo "<font><strong>Mis Puntos:<br>".$puntajes["Puntos"]."</strong></font>";
?>            
            	</td>
            </tr> 
		</table><br>
<?php 	
	footers();
	mysqli_close($xcon);
?>	
</html>	