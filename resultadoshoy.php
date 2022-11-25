<?php
	session_start();
	require_once 'datos/functions.php';
	//
	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	$hoy = date("Y-m-d");//
	//
	$xcon = conectarBd(); 	
	if (!$xcon) die('Error de Conexi�n: ('.mysqli_error($xcon).')');
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
	headers(); 	
	$sql2 = "SELECT * FROM participantes WHERE IdParticipante = '".$_SESSION["i"]."'";
	$r2 = mysqli_query($xcon, $sql2) or die("No se encontr&oacute; el usuario. Error: ".mysqli_error($xcon));
	if ($participantes = mysqli_fetch_array($r2, MYSQLI_ASSOC)) echo "Bienvenido(a): ".$participantes["Nombre"]."<br><br>";
	//	
	$sql3 = "SELECT * FROM partidos WHERE Fecha = '2022-06-15'";
	//$sql3 = "SELECT * FROM partidos WHERE Fecha = '".$hoy."'";
	$r3 = mysqli_query($xcon, $sql3);
	
	//Ubicar resultados de los partidos, un partido por tabla	
	while ($partidos = mysqli_fetch_array($r3, MYSQLI_ASSOC))
	{
?>
       	<table border="0">
			<tr><td colspan="3" style="font-family : Arial; font-size : 10pt; text-align : center;" ><?php echo $partidos["Lugar"]." - ".$partidos["Hora"]; ?></td></tr>
			<tr>
				<td style="font-family : Arial; font-size : 8pt; text-align : center;" width="87px"><?php echo $partidos["Equipo_1"]; ?></td>
				<td>&nbsp;</td>
				<td style="font-family : Arial; font-size : 8pt; text-align : center;" width="87px"><?php echo $partidos["Equipo_2"]; ?></td>
			</tr>
            <tr>
<?php
		$sql4 = "SELECT * FROM equipos WHERE Nombre = '".$partidos["Equipo_1"]."'";
		$r4 = mysqli_query($xcon, $sql4) or die("No se encontr&oacute; el Equipo_1. Error: ".mysqli_error($xcon));
		if ($datos2 = mysqli_fetch_array($r4, MYSQLI_ASSOC))
		{
			echo "<td align='center'><img src='".$datos2["Bandera"]."' width='70%'></td>
				  <td align='center'>";
   	    			if($partidos["Estado"] == "Jugado") echo $partidos["Resultado_1"];
					else echo "-";
			echo " - ";
					if($partidos["Estado"] == "Jugado") echo $partidos["Resultado_2"];
					else echo "-";
			echo "</td>";
		}
		$sql5 = "SELECT * FROM equipos WHERE Nombre = '".$partidos["Equipo_2"]."'";
		$r5 = mysqli_query($xcon, $sql5) or die("No se encontr&oacute; el Equipo_2. Error: ".mysqli_error($xcon));
		if ($datos3 = mysqli_fetch_array($r5, MYSQLI_ASSOC))
		{
			echo "<td align='center'><img src='".$datos3["Bandera"]."' width='70%'></td>";
		}
?>					
			</tr>
			<tr><td colspan="3" align="center">&nbsp;</td></tr>
		</table>
<?php
	}//Fin while ubicaci�n resultados
?>
		<br>
        Pron&oacute;sticos de partidos en la polla:
        <br><br>
<?php
	//Pron�sticos partidos hoy
	$sql6 = "SELECT * FROM partidos WHERE Fecha = '2022-06-15' AND Estado != 'Jugado'";
	//$sql6 = "SELECT * FROM partidos WHERE Fecha = '".$hoy."' AND Estado != 'Jugado'";
	$r6 = mysqli_query($xcon, $sql6);
	while ($partidos = mysqli_fetch_array($r6, MYSQLI_ASSOC))
	{
?>
       		<table border="1">
				<tr style="font-family : Arial; font-size : 10pt; text-align : center;" >
					<td rowspan="2">Nombre participante</td>
                    <td colspan="3">Pron&oacute;stico</td>
                </tr>
                <tr align="center">    
                    <td style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;' width="87px">
						<?php 	echo $partidos["Equipo_1"]."<br>";
								$sql7 = "SELECT * FROM equipos WHERE Nombre = '".$partidos["Equipo_1"]."'";
								$r7 = mysqli_query($xcon, $sql7) or die("No se encontr&oacute; el Equipo_1. Error: ".mysqli_error($xcon));
								if ($bandera1 = mysqli_fetch_array($r7, MYSQLI_ASSOC))
								{
									echo "<img src='".$bandera1["Bandera"]."' width='30%'>";
								} 
						?>
                    </td>
					<td>-</td>
					<td style='font-family:Tahoma, Geneva, sans-serif; font-size:7px;' width="87px">
						<?php 	echo $partidos["Equipo_2"]."<br>";
								$sql8 = "SELECT * FROM equipos WHERE Nombre = '".$partidos["Equipo_2"]."'";
								$r8 = mysqli_query($xcon, $sql8) or die("No se encontr&oacute; el Equipo_2. Error: ".mysqli_error($xcon));
								if ($bandera2 = mysqli_fetch_array($r8, MYSQLI_ASSOC))
								{
									echo "<img src='".$bandera2["Bandera"]."' width='30%'>";
								} 
						?>
                    </td>
				</tr>
<?php                			
		//Seleccionar todos los resultados en las pollas correspondientes al partido
		$sql9 = "SELECT * FROM resultados WHERE NoPartido = ".$partidos["NoPartido"];
		$r9 = mysqli_query($xcon, $sql9) or die("No se encontraron los resultados. Error: ".mysqli_error($xcon));
		while ($resultados = mysqli_fetch_array($r9, MYSQLI_ASSOC))
		{
			//Buscar el nombre de cada participante
			$sql10 = "SELECT * FROM participantes WHERE Idparticipante = '".$resultados["IdParticipante"]."'";
			$r10 = mysqli_query($xcon, $sql10) or die("No se encontr&oacute; el usuario. Error: ".mysqli_error($xcon));
			if ($participantes = mysqli_fetch_array($r10, MYSQLI_ASSOC))
			{
				echo "<tr style='font-family : Arial; font-size : 10pt;'>
						<td "; 
							if ($_SESSION["i"] == $resultados["IdParticipante"]) echo "bgcolor='#CACAF9'";
							echo ">".$participantes["Nombre"]."</td>
						<td ";
							if ($_SESSION["i"] == $resultados["IdParticipante"]) echo "bgcolor='#CACAF9'";	
							echo " align='center'>".$resultados["Resultado_1"]."</td>
						<td ";
							if ($_SESSION["i"] == $resultados["IdParticipante"]) echo "bgcolor='#CACAF9'";	
							echo " align='center'>-</td>
						<td ";
							if ($_SESSION["i"] == $resultados["IdParticipante"]) echo "bgcolor='#CACAF9'";	
							echo " align='center'>".$resultados["Resultado_2"]."</td>
					  </tr>";
			}//Fin if buscar nombre de cada participante
		}//Fin while resultados de un partido
?>

				</table><br><br>

<?php
	}//Fin while pron�sticos partidos hoy
	footers();
	mysqli_close($xcon);
?>	
</html>	