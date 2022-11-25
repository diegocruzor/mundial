<?php
	session_start();
	require_once 'datos/functions.php';
	//
	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	if (!$_POST['ing']) $ing = 0;
	else $ing = $_POST['ing'];
	//
	$xcon = conectarBd(); 	
	if (!$xcon) {
    die('Error de Conexión (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
	}
	//
	$sql1 = "SELECT * FROM participantes WHERE Idparticipante = '".$_SESSION['i']."'";;
	$r1 = mysqli_query($xcon, $sql1) or die("No se encuentra el usuario. Error: ".mysqli_error($sql));
	if ($datos1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)){}
	else 	header('Location: index.php');
	// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('UTC');
    $fase = 1; //de acuerdo a la fase en la que se encuentre el torneo se ingresarán los resultados de la polla
?>
<!DOCTYPE html Content-type: text/html; charset=utf-8>
<html lang="es">
<?php 
	headers();
	$sql2 = "SELECT * FROM participantes WHERE IdParticipante = '".$_SESSION["i"]."'";
	$r2 = mysqli_query($xcon, $sql2) or die("No se encontr&oacute; el usuario. Error: ".mysqli_error($xcon));
	if ($participantes = mysqli_fetch_array($r2, MYSQLI_ASSOC)) echo "Bienvenido(a): ".$participantes["Nombre"]."<br>al ingreso de participantes.<br><br>En esta secci&oacute;n ingresa la informaci&oacute;n de un nuevo usuario:<br>";
	if ($ing == 0) {
?>
				<div class="ContentForm">
		 			<form action="#" method="post" name="Form1">
	 				  	<p align="left"><br>Nombre completo: <input type="text" class="form-control" name="nombre" placeholder="Escriba el nombre completo del usuario" id="nombre" aria-describedby="sizing-addon1" required>
	 				  	<br>N&uacute;mero de documento:	<input type="text" class="form-control" name="idUsr" placeholder="Escriba el n&uacute;mero de documento del usuario" id="Usr" aria-describedby="sizing-addon1" required>
						<br>Correo electr&oacute;nico: <input type="email" class="form-control email" name="email" id="email" placeholder="name@example.com" required>
						<br>N&uacute;mero de celular: <input type="text" class="form-control" name="cel" id="cel" placeholder="Escriba el n&uacute;mero de celular" maxlength="10" size="10" required></p>
						<br><input type="hidden" name="ing" id="ing" value="1"><button class="btn btn-lg btn-primary btn-block btn-signin" id="Ingresar" type="submit">Ingresar</button>				
      				</form>
       			</div>
<?php
	}
else {
	$datUsr = array($_POST['nombre'], $_POST['idUsr'], $_POST['email'], $_POST['cel']);
	ingresoUsuario($xcon, $datUsr);
?>
				<div class="ContentForm">
		 			<form action="#" method="post" name="Form1">
	 					<input type="hidden" name="ing" id="ing" value="0"><button class="btn btn-lg btn-primary btn-block btn-signin" id="Ingresar" type="submit">Ingresar otro usuario</button>				
      				</form>
      			</div>
<?php }	
	footers();
	mysqli_close($xcon);
?>	
</html>	