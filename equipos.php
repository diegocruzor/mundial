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
	$r1 = mysqli_query($xcon, $sql1) or die("No se encontró el usuario. Error: ".mysqli_error($xcon));
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
	if ($participantes = mysqli_fetch_array($r2, MYSQLI_ASSOC)) echo "Bienvenido(a): ".$participantes["Nombre"]."<br>Aqu&iacute; encuentras las tablas de clasificaci&oacute;n:<br><br>";
	//Declarar letras para los grupos
	$grupos = array("A", "B", "C", "D", "E", "F", "G", "H");
	for ($i=0; $i < 8; $i++) { 
		echo cuadro($xcon, $grupos[$i]);
	}
	//
	footers();
	mysqli_close($xcon);
?>	
</html>	