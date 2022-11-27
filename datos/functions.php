<?php
require_once 'datos/config.php';
# Validar si se encuenra conectado un usuario registrado
//Si existe la sesión "user"..., la guardamos en una variable.
if (isset($_SESSION['i'])){
	$text=$_SESSION['i'];
}else{
	header('Location: index.php');//Aqui lo redireccionas al login para no ingresar a este apartado sino esta autenticado.
	die() ;
}
header('Content-Type: text/html; charset=UTF-8');
/*
if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
*/
if($xcon = conectarBd()){
	# Consulta haciendo uso de PDO
	$sql1 = $xcon->query("SELECT * FROM participantes WHERE Idparticipante = '".$_SESSION['i']."'");
	if($r = $sql1->fetch()) { /*echo "Funciona"; */ }
	else {
		$xcon = Null;
		header('Location: index.php');
	}
}
else header('Location: index.php'); 

// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
date_default_timezone_set('America/Bogota');

# FUNCIONES para el flujo de la infomración en la aplicación

# Función para mostrar opciones de configuración y acciones al admimistrador
function tipoUsuario($xcon, $idUsuario) {
	$sql = $xcon->query("SELECT * FROM participantes WHERE IdParticipante = '".$idUsuario."'");
	//$r = mysqli_query($xcon, $sql);
	if ($usuario = $sql->fetch()) $valor = $usuario["tipoUsuario"];
	if ($valor == 1){
		$imprimir = "<form name='form9' method='post' action='ingresoresultados.php'> 
        		<button class='btn btn-lg btn-primary btn-block btn-signin' id='IngResult' type='submit'>Ingreso Resultados</button>
        	</form>
        	<form name='form10' method='post' action='ingresarusuario.php'> 
        		<input type='hidden' name='ing' id='ing' value='0'><button class='btn btn-lg btn-primary btn-block btn-signin' id='IngResult2' type='submit'>Ingresar Usuario Nuevo</button>
        	</form>";
	}
	else $imprimir = "";
	return $imprimir;
}

# Función para seleccionar el grupo en el que se encuentran los equipós para los resultados
function seleccionarGrupo($xcon) {
	#$sql = "SELECT DISTINCT Grupo FROM resultados WHERE IdParticipante = '".$_SESSION["i"]."' AND EstadoResultado = 0 ORDER BY NoPartido ASC";
	#$sql = $xcon->query("SELECT DISTINCT Grupo FROM resultados WHERE IdParticipante = '".$_SESSION["i"]."' AND EstadoResultado = 0 ORDER BY NoPartido ASC");
	try {
		$sql = $xcon->query("SELECT min(Grupo) FROM resultados WHERE IdParticipante = '94533535' AND EstadoResultado = 0");
		if($grupo = $sql->fetch()) {
			return $grupo[0];
		}
		else return "A"; 	
	} 
	catch (Exception $e) {
		echo "Error: ".$e->getMessage();
	}	
}

# Función para imprimir en la aplicación los partidos de los eqipos en la primera fase
function impTblPronosticosFase1($xcon, $grupo) {
	$res[] = array();
	echo "<h3>Grupo ".$grupo."</h3>";
	$sql = $xcon->query("SELECT * FROM partidospronosticosqatar2022 WHERE grupo = '".$grupo."' ORDER BY idPartido ASC");
	while($partido = $sql->fetch()) {
		echo "<table border='0' style='font-family:Tahoma, Geneva, sans-serif; font-size:11px;' width='80%'>
				<tr>
					<td colspan='3' align='center' height='20'><i>".$partido["lugar"].", ".convertirFecha($partido["fecha"]).", ".$partido["hora"]."</i></td>
				</tr>
				<tr>";
		$sql2 = $xcon->query("SELECT * FROM equipos WHERE Id = '".$partido["idEquipo1"]."'");
		if ($datos = $sql2->fetch()) {
			echo "<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:10px;'>".$partido['Equipo1']."<br><img src='".$datos["Bandera"]."' width='50%'>
			<input type='text' name='R1[".$partido['idPartido']."]' placeholder='-' id='R1[".$partido['idPartido']."]' size='2' aria-describedby='sizing-addon1' style='text-align : center;' required></td><td>";
		}
		echo "<font size='5px'> - </font>";
		$sql3 = $xcon->query("SELECT * FROM equipos WHERE Id = '".$partido['idEquipo2']."'");
		if ($datos2 = $sql3->fetch()) {
			echo "</td><td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:10px;'>".$partido['Equipo2']."<br>
				<input type='text' name='R2[".$partido['idPartido']."]' placeholder='-' id='R2[".$partido['idPartido']."]' size='2' aria-describedby='sizing-addon1' style='text-align : center;' required>
				<img src='".$datos2["Bandera"]."' width='50%'></td></tr><tr><td colspan='3'>&nbsp;</td></tr>";
		}
	}
	echo "<tr><td colspan='3'><input type='hidden' name='f' value='".$grupo."'><button class='btn btn-lg btn-primary btn-block btn-signin' id='Sig' type='submit'>Siguiente</button></td></tr></table><br>";
}		  





# FUNCIONES para mostrar inormación en la aplicación

function headers(){
?>
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
			 <div class="Icon"><img src="images/LogoQatar.png"></span></div>
			<font face="Verdana, Geneva, sans-serif">
			<center>
        		<div class="ContentForm">
    				<h1>Acierta en Qatar 2022</h1>
					<form name="form_reloj"> 
						<input type="text" class="form-control" name="reloj" style="background-color : F3EDED; color : Black; font-family : Arial; font-size : 10pt; text-align : center;" aria-describedby="sizing-addon1" disabled> 
					</form>
<?php
}

function headers2(){
?>
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
     	<div id="Fase2">
			<div class="Icon"><img src="images/LogoQatar.png"></span></div>
			<font face="Verdana, Geneva, sans-serif">
			<center>
        		<div class="ContentForm">
    				<h1>Acierta en Qatar 2022</h1>
					<form name="form_reloj"> 
						<input type="text" class="form-control" name="reloj" style="background-color : F3EDED; color : Black; font-family : Arial; font-size : 10pt; text-align : center;" aria-describedby="sizing-addon1" disabled> 
					</form>
<?php
}

function footers() {
?>       
						</tbody>	
					</table>
        			<form name="volver" method="post" action="presentacion.php"> 
        				<button class="btn btn-lg btn-primary btn-block btn-signin" id="Volver" type="submit">Volver</button>
        			</form>
	 				____________________________________________
					<br><font face="Trebuchet MS, Arial, Helvetica, sans-serif" size="2">Dromasio &copy 2022</font>
				</div>
			</center>
			</font>
		</div>			
    </body>
	<!-- vinculando a libreria Jquery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Libreria java script de bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<?php	
}
unset($sql, $sql2, $sql3, $sql4, $sql5, $sql6, $sql7, $sql8, $sql9, $sql10, $sql11, $sql12, $sql13, $sql14);
unset($r, $r2, $r3, $r4, $r5, $r6, $r7, $r8, $r9, $r10, $r11, $r12, $r13, $r14);
?> 