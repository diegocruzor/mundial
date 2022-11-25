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
	<head>
		<title>Acierta en Qatar 2022</title>
		<script language="JavaScript" src="datos/date.js"></script> 
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<!-- vinculo a bootstrap -->
		<link rel="stylesheet" href="datos/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- Temas -->
		<link rel="stylesheet" href="datos/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="datos/estilo.css">
	</head>

	<body onLoad="Reloj()">
		<div id="Contenedor">
		<font face="Verdana, Geneva, sans-serif">
			<center>
			<div class="ContentForm">
				<div class="Icon"><img src="images/LogoQatar.png"></span></div>
				<h1>Acierta en Qatar 2022</h1>
				<form name="form_reloj"> 
					<input type="text" class="form-control" name="reloj" style="background-color : F3EDED; color : Black; font-family : Arial; font-size : 10pt; text-align : center;" aria-describedby="sizing-addon1" disabled> 
				</form>
				Pulsa sobre la opci&oacute;n deseada: <br><br>
				<form name="form2" method="post" action="clasificaciongeneral.php"> 
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="ClasifPolla" type="submit">Clasificaci&oacute;n Polla</button>
				</form>
				<form name="form5" method="post" action="fase2.php"> 
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="ClasifPolla" type="submit">Fase 2</button>
				</form>   
				<form name="form1" method="post" action="mispuntos.php"> 
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="MisPuntos" type="submit">Mis Puntos</button>
				</form>
				<form name="form6" method="post" action="puntosparticipantes.php"> 
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="PuntosPar" type="submit">Puntos Participantes</button>
				</form>
				<form name="form3" method="post" action="resultadoshoy.php"> 
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="ResHoy" type="submit">Resultados Hoy</button>
				</form>
				<?php echo tipoUsuario($xcon, $_SESSION['i']); ?>
				<!-- Este último formulario estaba en comentarios -->
				<form name="form8" method="post" action="mispronosticos.php"> 
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="ResHoy" type="submit">Ingreso Mis Pron&oacute;sticos</button>
				</form>
				<form name="form7" method="post" action="equipos.php"> 
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="Equipos" type="submit">Equipos</button>
				</form>   
				<form name="form4" method="post" action="index.php?p=0"> 
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="Salir" type="submit">Salir</button>
				</form>
				____________________________________________
				<font face="Trebuchet MS, Arial, Helvetica, sans-serif" size="2">Dromasio &copy 2022</font>
			</div>
			</center>
			</font>
		</div>		
	<?php mysqli_close($xcon); ?>						
</body>
	<!-- vinculando a libreria Jquery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Libreria java script de bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</html>	