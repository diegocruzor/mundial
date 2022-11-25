<?php
	session_start();
    require_once 'datos/functions.php';
	//
	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	$conta = 0;
	$contb = 0;
	$posa = 0;
	$posb = 0;
	$nombres = "";
	//
	$xcon = conectarBd(); 	
	if (!$xcon) {
    die('Error de Conexi&oacute;n (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
	}
	//
	$sql1 = "SELECT * FROM participantes WHERE Idparticipante = '".$_SESSION['i']."'";
	$r1 = mysqli_query($xcon, $sql1) or die("No se encontr&oacute; el usuario. Error".mysqli_error());
	if ($datos1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)){}
	else 	header('Location: index.php');
	// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('UTC');
?>
<!DOCTYPE html>
<?php headers(); ?> 
 			       	Clasificaci&oacute;n general en la polla:<br><br>
					<table class="table table-sm">
						<thead>
							<tr align="center">
								<th scope="col"><center>Posici&oacute;n</center></th>
								<th scope="col"><center>Nombre</center></th>
								<th scope="col"><center>Puntos</center></th>
							</tr>
						</thead>
						<tbody>
 <?php
 	$sql2 = "SELECT * FROM puntajes ORDER BY Puntos DESC";
	$r2 = mysqli_query($xcon, $sql2) or die("No se pueden seleccionar los puntajes. Error: ".mysqli_error());
	while ($puntajes = mysqli_fetch_array($r2, MYSQLI_ASSOC))
	{
		$posb = $puntajes["Puntos"];
		if ($posa == $posb) $contb++;
		else
		{
			$contb++;
			$conta = $contb;
		}
		$sql3 = "SELECT * FROM participantes WHERE Idparticipante = '".$puntajes["IdParticipante"]."'";
		$r3 = mysqli_query($xcon, $sql3) or die("No se encontr� el usuario. Error".mysqli_error());
		if ($participantes = mysqli_fetch_array($r3, MYSQLI_ASSOC))
		{
			echo "<tr align='center'>
					<th scope='row' "; 
						if ($_SESSION["i"] == $puntajes["IdParticipante"]) echo "bgcolor='#CACAF9'";
						echo "><center>".$conta."</center></th>
					<td ";
						if ($_SESSION["i"] == $puntajes["IdParticipante"]) echo "bgcolor='#CACAF9'";	
						echo ">".$participantes["Nombre"]."</td>
					<td ";
						if ($_SESSION["i"] == $puntajes["IdParticipante"]) echo "bgcolor='#CACAF9'";	
						echo ">".$puntajes["Puntos"]."</td>
				  </tr>";
			$posa = $puntajes["Puntos"];
		}
	}
	footers();
	mysqli_close($xcon);
?>	
</html>	