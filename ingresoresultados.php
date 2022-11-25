<?php
	session_start();
	require_once 'datos/functions.php';
	//
	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	$hoy = date("Y-m-d");
	$cont = 0;
	$puntos = 0;
	//
	$xcon = conectarBd(); 	
	if (!$xcon) die('Error de Conexión: '.mysqli_error($xcon));
	//
	$sql1 = "SELECT * FROM participantes WHERE Idparticipante = '".$_SESSION['i']."'";;
	$r1 = mysqli_query($xcon, $sql1) or die("No se encuentra el usuario. Error: ".mysqli_error($xcon));
	if ($datos1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)){}
	else header('Location: index.php');
	//
	if (!isset($_GET['p'])) $_SESSION['p'] = 0;
	else $_SESSION['p'] = $_GET['p'];
	
	ingresarResultado($xcon, $_SESSION['p']);
	
	// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Bogota');
    //Imprimimos la fecha actual dandole un formato
?>
<!DOCTYPE html Content-type: text/html; charset=utf-8>
<html lang="es">
<?php headers(); ?>	
	Por favor ingresar la informaci&oacute;n solicitada:<br><br>
<?php
	mostrarEncuentros($xcon, $hoy);
	footers();
	mysqli_close($xcon);
?>	
</html>	