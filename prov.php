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
	$r1 = mysqli_query($xcon, $sql1) or die("No se encontr� el usuario. Error: ".mysqli_error($xcon));
	if ($datos1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)){}
	else 	header('Location: index.php');
	// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('UTC');
    $fase = 1; //de acuerdo a la fase en la que se encuentre el torneo se ingresarán los resultados de la polla
?>
<!DOCTYPE html Content-type: text/html; charset=utf-8>
<html lang="es">
<?php headers(); ?>	
				<div id="TblMisPronosticos">	
<?php
	$sql2 = "SELECT * FROM participantes WHERE IdParticipante = '".$_SESSION["i"]."'";
	$r2 = mysqli_query($xcon, $sql2) or die("No se encontr� el usuario. Error: ".mysqli_error($xcon));
	if ($participantes = mysqli_fetch_array($r2, MYSQLI_ASSOC)) echo "Bienvenido(a): ".$participantes["Nombre"]."<br>a tu pron&oacute;stico de partidos jugados<br><br>Los datos suministrados son:<br>";
	//if ($f != "") ingresarPronosticos($xcon);
	if ($f != "") {
		$r1 = $_POST['R1'];
		$r2 = $_POST['R2'];
		//
		echo "Resultado1_Pardido => Valor<br>";
		foreach ($r1 as $res1 => $value) {
			echo $res1." => ".$value."<br>";
			$sql = "UPDATE resultados SET Resultado_1 = ".$value." WHERE IdParticipante = '".$_SESSION["i"]."' AND NoPartido = ".$res1;
			echo $sql."<br><br>";
		}
		echo "<br><br>Resultado2_Pardido => Valor<br>";
		foreach ($r2 as $res2 => $value) {
			echo $res2." => ".$value."<br>";
			$sql = "UPDATE resultados SET Resultado_2 = ".$value.", EstadoResultado = 1 WHERE IdParticipante = '".$_SESSION["i"]."' AND NoPartido = ".$res2;
			echo $sql."<br><br>";
		}
	}

	
	impTblPronosticosFase1($xcon, "A", $f);
	echo "</div>";
	footers();
	mysqli_close($xcon);
?>	
</html>	