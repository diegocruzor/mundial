<?php
	session_start();
	require_once 'datos/functions.php';
	//
	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	if (!isset($_POST['f'])) $f = "";
	else $f = $_POST['f'];
	$xcon = conectarBd(); 	
	if (!$xcon) {
    die('Error de Conexión (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
	}
	//
	$sql1 = "SELECT * FROM participantes WHERE Idparticipante = '".$_SESSION['i']."'";;
	$r1 = mysqli_query($xcon, $sql1) or die("No se encontr&oacute; el usuario. Error: ".mysqli_error($xcon));
	if ($datos1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)){}
	else 	header('Location: index.php');
	// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('UTC');
    if ($f != "") ingresarPronosticos($xcon);
	$grupo = seleccionarGrupo($xcon);
?>
<!DOCTYPE html Content-type: text/html; charset=utf-8>
<html lang="es">
<?php headers(); ?>	
				<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><strong>Informaci&oacute;n Importante:</strong> Todos los pronósticos correspondientes a esta fase se deben ingresar en su totalidad hasta antes de finalizar el jueves 24 de noviembre del 2022, a partir del viernes 25 de noviembre del 2022 ya no hay posibilidades de ingresar más resultados.</div>
				<div id="TblMisPronosticos">	
<?php
	$sql2 = "SELECT * FROM participantes WHERE IdParticipante = '".$_SESSION["i"]."'";
	$r2 = mysqli_query($xcon, $sql2) or die("No se encontr&oacute; el usuario. Error: ".mysqli_error($xcon));
	if ($participantes = mysqli_fetch_array($r2, MYSQLI_ASSOC)) echo "Bienvenido(a): ".$participantes["Nombre"]."<br>a tu pron&oacute;stico de partidos jugados.<br><br><p style='text-align:left'>Ingresa en esta secci&oacute;n tus resultados teniendo en cuenta que debes diligenciar <u>todas las casillas</u>, una vez pulses el botón siguiente ya no se podrán realizar modificaciones a los resultados, cada página contiene los partidos por grupos:</p>";
	echo "<form name='IngresoPronostico' method='post' action='mispronosticos.php'>";
	impTblPronosticosFase1($xcon, $grupo);
	echo "</form></div>";
	footers();
	mysqli_close($xcon);
?>	
</html>	