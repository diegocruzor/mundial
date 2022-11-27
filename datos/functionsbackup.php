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


//para la sección equipos, construcción de cada tabla
function cuadro($xcon, $letra) {
    
	echo"<strong>GRUPO ".$letra."</strong><br><br>		
		<table class='table table-striped' style='font-family:Tahoma, Geneva, sans-serif; font-size:75%;' width='100%'>
        	<thead>	
        		<tr>
        			<th>Equipo</td>
            		<th>Pj</td>
            		<th>Pg</td>
            		<th>Pe</td>
            		<th>Pp</td>
            		<th>Gf</td>
            		<th>Gc</td>
            		<th>Di</td>
            		<th>Pts</td>
				</tr>
			</thead><tbody>";

	//Seleccionar todos los equipos del Grupo A
	$sql = "SELECT * FROM equipos WHERE Grupo = '".$letra."' ORDER BY PTS DESC, GF DESC";
	$r = mysqli_query($xcon, $sql) or die("No se encontraron los equipos. Error".mysql_error());
	//while equipos 
	while ($equipos = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
		//La siguiente es una muy útil combinación entre elementos de una consulta y caracteres dentro del resultado obtenido
		$grupoa = $equipos["Id"]{0};
		
        echo "<tr>
        		<td><img src='".$equipos["Bandera"]."' width='20%'> ".$equipos["Nombre"]."</td>
	            <td align='center'>".$equipos["PJ"]."</td>
	            <td align='center'>".$equipos["PG"]."</td>
	            <td align='center'>".$equipos["PE"]."</td>
	            <td align='center'>".$equipos["PP"]."</td>
	            <td align='center'>".$equipos["GF"]."</td>
	            <td align='center'>".$equipos["GC"]."</td>
	            <td align='center'>".$equipos["DI"]."</td>
	            <td align='center'>".$equipos["PTS"]."</td>
			</tr>";
	}//Fin while equipos
 
	return "</tbody></table><br>";	
}

function impOctavos($xcon, $nPartido, $eqOctavos){
	
	echo "<font size='1%'>";
	$sql = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Id='".$eqOctavos."'";
	$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; equipo ".$eqOctavos.". Error".mysql_error());
	if ($equipos_octavos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		if ($equipos_octavos["Nombre"] == $eqOctavos) echo $eqOctavos."<br><input type='text' name='".$eqOctavos[0]."' value='";
		else {
			echo $equipos_octavos["Nombre"]."<br>
				<img src='".$equipos_octavos["Bandera"]."' width='40%'>
				<input type='text' name='Res_".$eqOctavos."' value='";
		}
		//imprimir el resultado del partido
		$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
		$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido ".$nPartido.". Error: ".mysql_error());
		if ($resultado_partido = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			if ($eqOctavos[0]==1) { 
				if ($resultado_partido["Estado"] == "Jugado") echo $resultado_partido["Resultado_1"]; 
				else echo "-";
			}	
			else { 
				if ($resultado_partido["Estado"] == "Jugado") echo $resultado_partido["Resultado_2"]; 
				else echo "-";
			}
		}
	}
	return "' size='1' maxlength='2' style='text-align : center;' disabled></font>";	
}

function impCuartos($xcon, $nPartido, $letra){ //Ej: letra = "I" o "K" partido 57
	
	echo "<font size='1%'>";
	$sql= "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
	$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido ".$nPartido.". Error".mysql_error());
	if ($partidos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		if(noEqCuartos($letra) == 1){ //local
			if (letraDeEquipo($partidos["Equipo_1"])) echo $partidos["Equipo_1"]."<br><input type='text' name='".$partidos["Equipo_1"]."_Res' value='";
			else {
				$sql = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Cuartos = '".$letra."'";
				$r= mysqli_query($xcon, $sql) or die("No se encontr&oacute; equipo ".$partidos["Equipo_1"]." en campo Cuartos tabla equipos_octavos. Error: ".mysql_error());
				if ($equipos_octavos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					echo $equipos_octavos["Nombre"]."<br>
						<img src='".$equipos_octavos["Bandera"]."' width='40%'>
			   	 		<input type='text' name='".$partidos["Equipo_1"]."_Res' value='";
				}
			}
			//imprimir el resultado del equipo en el partido
			$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
			$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido ".$nPartido.". Error".mysql_error());
			if ($resultado_partido = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				if ($resultado_partido["Estado"] == "Jugado") echo $resultado_partido["Resultado_1"]; 
				else echo "-";
			}	
		}
		else { //visitante
			if (letraDeEquipo($partidos["Equipo_2"])) echo $partidos["Equipo_2"]."<br><input type='text' name='".$partidos["Equipo_2"]."_Res' value='";
			else {
				$sql = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Cuartos = '".$letra."'";
				$r= mysqli_query($xcon, $sql) or die("No se encontr&oacute; equipo ".$partidos["Equipo_2"]." en campo Cuartos tabla equipos_octavos. Error: ".mysql_error());
				if ($equipos_octavos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					echo $equipos_octavos["Nombre"]."<br>
						<img src='".$equipos_octavos["Bandera"]."' width='40%'>
			   	 		<input type='text' name='".$partidos["Equipo_2"]."_Res' value='";
				}
			}
			//imprimir el resultado del equipo en el partido
			$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
			$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido ".$nPartido.". Error".mysql_error());
			if ($resultado_partido = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				if ($resultado_partido["Estado"] == "Jugado") echo $resultado_partido["Resultado_2"]; 
				else echo "-";
			}	
		}
	}
	return "' size='1' maxlength='2' style='text-align : center;' disabled></font>";	
}

function impSemifinal($xcon, $nPartido, $letra) { //Ej: letra = "Q1" o "Q3" partido 61
	
	echo "<font size='1%'>";
	$nEq = 2;
	if ($letra == "Q1" || $letra == "Q2") $nEq = 1; 
	$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
	$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido ".$nPartido.". Error".mysql_error());
	if ($partidos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		if($nEq == 1) { //local
			if (letraDeEquipo($partidos["Equipo_1"])) echo $partidos["Equipo_1"]."<br><input type='text' name='".$partidos["Equipo_1"]."_Res' value='";
			else {
				$sql = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Semifinal = '".$letra."'";
				$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; equipo ".$letra." en Semifinal. Error".mysql_error());
				if ($equipos_octavos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					echo $equipos_octavos["Nombre"]."<br>
						<img src='".$equipos_octavos["Bandera"]."' width='40%'>
		    			<input type='text' name='".$partidos["Equipo_1"]."_Res' value='";
				}
			}
			//imprimir el resultado del partido
			$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
			$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido ".$nPartido.". Error".mysql_error());
			if ($resultado_partido = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				if ($resultado_partido["Estado"] == "Jugado") echo $resultado_partido["Resultado_1"]; 
				else echo "-";
			}
		}	
		else { //visitante
			if (letraDeEquipo($partidos["Equipo_2"])) echo $partidos["Equipo_2"]."<br><input type='text' name='".$partidos["Equipo_2"]."_Res' value='";
			else {
				$sql = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Semifinal = '".$letra."'";
				$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; equipo ".$letra." en Semifinal. Error".mysql_error());
				if ($equipos_octavos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					echo $equipos_octavos["Nombre"]."<br>
						<img src='".$equipos_octavos["Bandera"]."' width='40%'>
		    			<input type='text' name='".$partidos["Equipo_2"]."_Res' value='";
				}
			}
			//imprimir el resultado del partido
			$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
			$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido ".$nPartido.". Error".mysql_error());
			if ($resultado_partido = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				if ($resultado_partido["Estado"] == "Jugado") echo $resultado_partido["Resultado_2"]; 
				else echo "-";
			}
		}
  	}
	return "' size='1' maxlength='2' style='text-align : center;' disabled></font>";	
}

function impPartidoTer($xcon, $nPartido, $letra) { //Ej: letra = "LS1", partido = 63

	echo "<font size='1%'>";
	$nEq = 2;
	if ($letra == "LS1" || $letra =="WS1") $nEq = 1; 
	$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
	$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido ".$nPartido.". Error".mysql_error());
	if ($partidos = mysqli_fetch_array($r, MYSQLI_ASSOC)) { 
		if ($nEq == 1) { //local
			if (letraDeEquipo($partidos["Equipo_1"])) echo $partidos["Equipo_1"]."<br><input type='text' name='".$partidos["Equipo_1"]."_Res' value='";
			else {
				$sql = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Final = '".$letra."'";
				$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; equipo ".$letra." en Final. Error".mysql_error());
				if ($equipos_octavos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					echo $equipos_octavos["Nombre"]."<br>
						<img src='".$equipos_octavos["Bandera"]."' width='40%'>
			    		<input type='text' name='".$partidos["Equipo_1"]."_Res' value='";
				}
			}
			//imprimir el resultado del partido
			$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
			$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido 63. Error".mysql_error());
			if ($resultado_partido = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				if ($resultado_partido["Estado"] == "Jugado") echo $resultado_partido["Resultado_1"]; 
				else echo "-";
			}
		}
		else { //visitante
			if (letraDeEquipo($partidos["Equipo_2"])) echo $partidos["Equipo_2"]."<br><input type='text' name='".$partidos["Equipo_2"]."_Res' value='";
			else {
				$sql = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Final = '".$letra."'";
				$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; equipo ".$letra." en Final. Error".mysql_error());
				if ($equipos_octavos = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					echo $equipos_octavos["Nombre"]."<br>
						<img src='".$equipos_octavos["Bandera"]."' width='40%'>
			    		<input type='text' name='".$partidos["Equipo_2"]."_Res' value='";
				}
			}
			//imprimir el resultado del partido
			$sql = "SELECT * FROM partidos WHERE NoPartido = ".$nPartido;
			$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; partido 63. Error".mysql_error());
			if ($resultado_partido = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				if ($resultado_partido["Estado"] == "Jugado") echo $resultado_partido["Resultado_2"]; 
				else echo "-";
			}
		}
	}
	return "' size='1' maxlength='2' style='text-align : center;' disabled></font>";	
}

function letraDeEquipo($letraDeEquipo) {
	
	$letraEq = array("I", "J", "K", "L", "M", "N", "O", "P", "Q1", "Q2", "Q3", "Q4", "LS1", "LS2", "WS1", "WS2");
	$eqSinDefinir = false;
	foreach ($letraEq as $k) { if ($k == $letraDeEquipo) $eqSinDefinir = true; } 
	unset($k);
	return $eqSinDefinir;
}

function noEqCuartos($letra) {
	
	$cuartosLocal = array ("I", "M", "J", "N");
	$nEq = 2;
	foreach ($cuartosLocal as $k) { if ($k == $letra) $nEq = 1; } 
	unset($k);
	return $nEq;
}

function impTblPronosticosFase1($xcon, $grupo) {
	$res[] = array();
	echo "<h3>Grupo ".$grupo."</h3>";
	$sql = "SELECT * FROM partidospronosticosqatar2022 WHERE grupo = '".$grupo."' ORDER BY idPartido ASC";
	$r = mysqli_query($xcon, $sql) or die("No se encontr&oacute; el grupo ".$grupo.". Error".mysqli_error($xcon));
	while($partido = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo "<table border='0' style='font-family:Tahoma, Geneva, sans-serif; font-size:11px;' width='80%'>
				<tr>
					<td colspan='3' align='center' height='20'><i>".$partido["lugar"].", ".convertirFecha($partido["fecha"]).", ".$partido["hora"]."</i></td>
				</tr>
				<tr>";
		$sql2 = "SELECT * FROM equipos WHERE Id = '".$partido["idEquipo1"]."'";
		$r2 = mysqli_query($xcon, $sql2);
		if ($datos = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
			echo "<td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:10px;'>".$partido['Equipo1']."<br><img src='".$datos["Bandera"]."' width='50%'>
			<input type='text' name='R1[".$partido['idPartido']."]' placeholder='-' id='R1[".$partido['idPartido']."]' size='2' aria-describedby='sizing-addon1' style='text-align : center;' required></td><td>";
		}
		echo "<font size='5px'> - </font>";
		$sql3 = "SELECT * FROM equipos WHERE Id = '".$partido['idEquipo2']."'";
		$r3 = mysqli_query($xcon, $sql3);
		if ($datos2 = mysqli_fetch_array($r3, MYSQLI_ASSOC)) {
			echo "</td><td align='center' style='font-family:Tahoma, Geneva, sans-serif; font-size:10px;'>".$partido['Equipo2']."<br>
				<input type='text' name='R2[".$partido['idPartido']."]' placeholder='-' id='R2[".$partido['idPartido']."]' size='2' aria-describedby='sizing-addon1' style='text-align : center;' required>
				<img src='".$datos2["Bandera"]."' width='50%'></td></tr><tr><td colspan='3'>&nbsp;</td></tr>";
		}
	}
	echo "<tr><td colspan='3'><input type='hidden' name='f' value='".$grupo."'><button class='btn btn-lg btn-primary btn-block btn-signin' id='Sig' type='submit'>Siguiente</button></td></tr></table><br>";
}		  



# <!-- Continuar desde aquí -->


function ingresarPronosticos($xcon) {
	$r1 = $_POST['R1'];
	$r2 = $_POST['R2'];
	//
	foreach ($r1 as $res1 => $value) {
		$sql = "UPDATE resultados SET Resultado_1 = ".$value." WHERE IdParticipante = '".$_SESSION["i"]."' AND NoPartido = ".$res1;
		$r = mysqli_query($xcon, $sql) or die('No es posible ingresar la informaci&oacute;n en este momento, int&eacute;ntalo m&aacute;s tarde. Error: '.mysqli_error($xcon));
	}
	foreach ($r2 as $res2 => $value) {
		$sql = "UPDATE resultados SET Resultado_2 = ".$value.", EstadoResultado = 1 WHERE IdParticipante = '".$_SESSION["i"]."' AND NoPartido = ".$res2;
		$r = mysqli_query($xcon, $sql) or die('No es posible ingresar la informaci&oacute;n en este momento, int&eacute;ntalo m&aacute;s tarde. Error: '.mysqli_error($xcon));
	}

	/*
	$sql = $conn->prepare("SELECT COUNT(*) AS num_rows FROM zip WHERE zip_code = :zip");
	$sql->execute();
	$count = (int)$sql->fetchColumn();
	if($count) echo "Success";
	else echo "Fail";
	*/
}


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

function convertirFecha($fecha) {
	
	$mesTexto = array ("Anio", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
	$fechaDet = explode("-", $fecha);
	$mes = (int) $fechaDet[1];
	$fechaTexto = $fechaDet[2]." de ".$mesTexto[$mes]." de ".$fechaDet[0];
	return $fechaTexto;
}

function ingresoUsuario($xcon, $datUsr) {
	$sql = "INSERT INTO participantes VALUES('$datUsr[1]', '$datUsr[0]', $datUsr[3], '$datUsr[2]', 0)";
	$r = mysqli_query($xcon, $sql);
	
	if (!$r) echo '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><strong>Error: </strong>No es posible ingresar el usuario. Es posible que ya haya sido registrado. Error: '.mysqli_error($xcon).'</div>';
	else {
		/*
		$cont = 0;
		$sql2 = "SELECT * FROM resultados ORDER BY IdResultado ASC";
		$r2 = mysqli_query($xcon, $sql2);
		if (!$r2) echo '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><strong>Error: </strong>No se encontraron registros en tabla resultados. Error: '.mysqli_error($xcon).'</div>';
		else $cont = mysqli_num_rows($r2);
		*/
		# IMPORTANTE: Inclir esta sentencia cuando ya se finalicen las pruebas
		$sql2 = "SELECT * FROM partidos WHERE ESTADO = 'Por Jugar' ORDER BY NoPartido ASC";
		$r2 = mysqli_query($xcon, $sql2);
		while ($partido = mysqli_fetch_row($r2)){
			//$cont++;
			//$sql3 = "INSERT INTO resultados (IdParticipante, NoPartido, Equipo_1, Equipo_2, Grupo, EstadoResultado) VALUES(".$cont.",'".$datUsr[1]."',".$partido[0].",'".$partido[1]."','".$partido[2]."','".$partido[5]."', 0)";
			$sql3 = "INSERT INTO resultados (IdParticipante, NoPartido, Equipo_1, Equipo_2, Grupo, EstadoResultado) VALUES('".$datUsr[1]."',".$partido[0].",'".$partido[1]."','".$partido[2]."','".$partido[5]."', 0)";
			$r3 = mysqli_query($xcon, $sql3);
			if (!$r3) echo '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><strong>Error: </strong>No es posible ingresar resultados. Error: '.mysqli_error($xcon).'</div>';
		}
		
		$sql3 = "INSERT INTO puntajes VALUES('".$datUsr[1]."', 0)";
		$r3 = mysqli_query($xcon, $sql3);
		if (!$r3) echo '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><strong>Error: </strong>No es posible ingresar resultados. Error: '.mysqli_error($xcon).'</div>';
		echo '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>Ingreso de usuario exitoso.</div>';
	}
}

function ingresarResultado($xcon, $partido) {

	if ($partido != 0) {
		//El siguiente procedimiento condicionará si el partido ya fue jugado para que el resultado y los puntos no se alteren
		$sql1 = "SELECT * FROM partidos WHERE NoPartido = ".$partido;
		$r1 = mysqli_query($xcon, $sql1) or die ("No se puede seleccionar el partido. Error: ".mysqli_error($xcon));
		if($datos1 = mysqli_fetch_array($r1, MYSQLI_ASSOC))	{
			//El siguiente If condicionará si el partido ya fue jugado para que el resultado y los puntos no se alteren
			if ($datos1['Estado'] != "Jugado") {
				$R1 = $_POST["R1-".$partido];
				$R2 = $_POST["R2-".$partido];
				//Condicionar que se ingresen los resultados en los campos
				if ($R1 != "-" && $R2 != "-") {
					//Modificar el marcador y el estado en el partido finalizado
					$sql2 = "UPDATE partidos SET Resultado_1 = ".$R1.", Resultado_2 = ".$R2.", Estado = 'Jugado' WHERE NoPartido = ".$partido;
					$r2 = mysqli_query($xcon, $sql2) or die ("No se puede ingresar el resultado. Error: ".mysqli_error($xcon));
					//Modificar goles y puntos en Equipo_1
					$sql3 = "SELECT * FROM equipos WHERE Nombre = '".$datos1["Equipo_1"]."'";
					$r3 = mysqli_query($xcon, $sql3) or die ("No es posible seleccionar Equipo_1. Error: ".mysqli_error($xcon));
					if ($datos3 = mysqli_fetch_array($r3, MYSQLI_ASSOC)) {
						//PJ PG PE PP GF GC DI PTS
						$GF = $R1; //Goles a favor en Equipo_1 en el partido que se est� registrando
						$GC = $R2; //Goles en contra en Equipo_1 en el partido que se est� registrando
						//Consolidar goles a favor y goles en contra
						$GF = $GF + $datos3['GF'];
						$GC = $GC + $datos3['GC'];
						//Consolidar diferencia de goles
						$DIn = $GF - $GC;
						if ($DIn > 0) $DI = "+".$DIn;
						elseif ($DIn == 0) $DI = 0;
						else $DI = $DIn;
						//Consolidar Partidos Jugados, Partidos Ganados, Partidos Perdidos y Puntos obtenidos
						//Recuperar valores de la tabla equipos
						$PJ = $datos3['PJ'];
						$PG = $datos3['PG'];
						$PE = $datos3['PE'];
						$PP = $datos3['PP'];
						$PTS = $datos3['PTS'];
						//Aumentar cantidad de partidos jugados
						$PJ++;
						//Verificar según resultado la variable que aumenta en 1 y los puntos obtenidos
						//En el caso que gane Equipo_1
						if ($R1 > $R2) {
							$PG++;
							$PTS+=3;
						}
						//En caso de empate
						elseif ($R1 == $R2) {
							$PE++;
							$PTS++;
						}
						//En el caso que pierda Equipo_1
						else {
							$PP++;
						}
						//SEGUIR REVISANDO DESDE AQUÍ PARA IMPLEMENTAR EN ingresoresultados.php
	
						//Adicionar los goles que se encuentran en la tabla Equipos
						//echo $datos3['Nombre'].": ".$PJ." - ".$PG." - ".$PE." - ".$PP." - ".$GF." - ".$GC." - ".$DI." - ".$PTS;
						//Por �ltimo ejecutar el UPDATE antes de salir del If
						$sql4 = "UPDATE equipos 
								SET PJ = ".$PJ.", 
									PG = ".$PG.", 
									PE = ".$PE.", 
									PP = ".$PP.", 
									GF = ".$GF.", 
									GC = ".$GC.", 
									DI = '".$DI."', 
									PTS = ".$PTS." 
								WHERE Nombre = '".$datos1["Equipo_1"]."'";
						$r4 = mysqli_query($xcon, $sql4) or die ("No se puede ingresar el resultado. Error: ".mysqli_error($xcon));
					}//Fin if modificaci�n de goles y puntos en Equipo_1
					
					//Modificar goles y puntos en Equipo_2
					$sql5 = "SELECT * FROM equipos WHERE Nombre = '".$datos1["Equipo_2"]."'";
					$r5 = mysqli_query($xcon, $sql5) or die ("No es posible seleccionar Equipo_1. Error: ".mysqli_error($xcon));
					if ($datos5 = mysqli_fetch_array($r5, MYSQLI_ASSOC)) {
						//PJ PG PE PP GF GC DI PTS
						$GF = $R2; //Goles a favor en Equipo_1 en el partido que se est� registrando
						$GC = $R1; //Goles en contra en Equipo_1 en el partido que se est� registrando
						//Consolidar goles a favor y goles en contra
						$GF = $GF + $datos5['GF'];
						$GC = $GC + $datos5['GC'];
						//Consolidar diferencia de goles
						$DIn = $GF - $GC;
						if ($DIn > 0) $DI = "+".$DIn;
						elseif ($DIn == 0) $DI = 0;
						else $DI = $DIn;
						//Consolidar Partidos Jugados, Partidos Ganados, Partidos Perdidos y Puntos obtenidos
						//Recuperar valores de la tabla equipos
						$PJ = $datos5['PJ'];
						$PG = $datos5['PG'];
						$PE = $datos5['PE'];
						$PP = $datos5['PP'];
						$PTS = $datos5['PTS'];
						//Aumentar cantidad de partidos jugados
						$PJ++;
						//Verificar seg�n resultado la variable que aumenta en 1 y los puntos obtenidos
						//En el caso que gane Equipo_1
						if ($R2 > $R1)
						{
							$PG++;
							$PTS+=3;
						}
						//En caso de empate
						elseif ($R2 == $R1)
						{
							$PE++;
							$PTS++;
						}
						//En el caso que pierda Equipo_1
						else
						{
							$PP++;
						}
						//Adicionar los goles que se encuentran en la tabla Equipos
						//echo $datos5['Nombre'].": ".$PJ." - ".$PG." - ".$PE." - ".$PP." - ".$GF." - ".$GC." - ".$DI." - ".$PTS;
						//Por �ltimo ejecutar el UPDATE antes de salir del If
						$sql6 = "UPDATE equipos 
								SET PJ = ".$PJ.", 
									PG = ".$PG.", 
									PE = ".$PE.", 
									PP = ".$PP.", 
									GF = ".$GF.", 
									GC = ".$GC.", 
									DI = '".$DI."', 
									PTS = ".$PTS." 
								WHERE Nombre = '".$datos1["Equipo_2"]."'";
						$r6 = mysqli_query($xcon, $sql6) or die ("No se puede ingresar el resultado. Error: ".mysqli_error($xcon));
					}//Fin if modificaci�n de goles y puntos en Equipo_2
				
					//Registrar Ganador
					//Si gana el partido Equipo_1
					if ($R1 > $R2)
					{
						$sql7 = "UPDATE partidos SET Ganador = '".$datos1["Equipo_1"]."' WHERE NoPartido = ".$partido;
						$r7 = mysqli_query($xcon, $sql7) or die ("No se puede ingresar el resultado. Error: ".mysqli_error($xcon));
					}
					//Si gana el partido Equipo_2
					elseif ($R1 < $R2)
					{
						$sql7 = "UPDATE partidos SET Ganador = '".$datos1["Equipo_2"]."' WHERE NoPartido = ".$partido;
						$r7 = mysqli_query($xcon, $sql7) or die ("No se puede ingresar el resultado. Error: ".mysqli_error($xcon));
					}
					//Si hay empate
					else
					{
						$sql7 = "UPDATE partidos SET Ganador = 'Empate' WHERE NoPartido = ".$partido;
						$r7 = mysqli_query($xcon, $sql7) or die ("No se puede ingresar el resultado. Error: ".mysqli_error($xcon));
					}//Fin registro de ganador
				
					//Consolidar puntos para cada participante
					//Seleccionar todos los participantes
					$sql8 = "SELECT * FROM participantes";
					$r8 = mysqli_query($xcon, $sql8) or die("No se pueden seleccionar los participantes. Error: ".mysqli_error($xcon));
					while ($participantes = mysqli_fetch_array($r8, MYSQLI_ASSOC)) {	
						//Seleccionar el partido al que se le ingres� el resultado final
						$sql9 = "SELECT * FROM partidos WHERE NoPartido = ".$partido;
						$r9 = mysqli_query($xcon, $sql9) or die("No se puede seleccionar el partido. Error: ".mysqli_error($xcon));
						if ($partidos = mysqli_fetch_array($r9, MYSQLI_ASSOC)) {
							//Obtener el resultado ingresado por el participante en el partido
							$sql10 = "SELECT * FROM resultados WHERE IdParticipante = '".$participantes["IdParticipante"]."' AND NoPartido = ".$partido;
							$r10 = mysqli_query($xcon, $sql10) or die("No se puede seleccionar el resultado. Error: ".mysqli_error($xcon));
							if ($resultados = mysqli_fetch_array($r10, MYSQLI_ASSOC)) {
								$puntosGanados = 0;
								//Comparar resultados e ingresar datos a la tabla puntos por partido
								//Si acierta tanto equipos como resultados se suman dos puntos
								if (($partidos["Equipo_1"] == $resultados["Equipo_1"]) && ($partidos["Resultado_1"] == $resultados["Resultado_1"]) && ($partidos["Equipo_2"] == $resultados["Equipo_2"]) && ($partidos["Resultado_2"] == $resultados["Resultado_2"])) $puntosGanados += 2;
								elseif (($partidos["Equipo_1"] == $resultados["Equipo_1"]) && ($partidos["Equipo_2"] == $resultados["Equipo_2"])) {
									//En caso de empate sin acertar el resultado
									if (($partidos["Resultado_1"] == $partidos["Resultado_2"]) && ($resultados["Resultado_1"] == $resultados["Resultado_2"])) $puntosGanados ++;
									//En caso de acertar el ganador
									elseif (($partidos["Resultado_1"] > $partidos["Resultado_2"]) && ($resultados["Resultado_1"] > $resultados["Resultado_2"])) $puntosGanados++;
									elseif (($partidos["Resultado_1"] < $partidos["Resultado_2"]) && ($resultados["Resultado_1"] < $resultados["Resultado_2"])) $puntosGanados++;
									else $puntosGanados=0;
								}
								//obtener id del ultimo registro en puntosporpartido
								$sql11 = "SELECT COUNT(*) FROM puntosporpartido";
								$r11 = mysqli_query($xcon, $sql11) or die("No se pueden contar los registros. Error: ".mysqli_error($xcon));
								if ($idppp = mysqli_fetch_row($r11)) $cont = $idppp[0] + 1;
								else $cont = 1;							
								//ingresar datos a la tabla puntosporpartido
								$sql12 = "INSERT INTO puntosporpartido VALUES(".$cont.", '".$participantes["IdParticipante"]."',".$partidos["NoPartido"].", ".$puntosGanados.")";
								$r12 = mysqli_query($xcon, $sql12) or die("No se puede insertar el registro en la tabla puntosporpartido. Error: ".mysqli_error($xcon));
								
								//Sumar los puntos obtenidos y modificarlos en la tabla puntajes
								$sql13 = "SELECT * FROM puntajes WHERE IdParticipante = '".$participantes["IdParticipante"]."'";
								$r13 = mysqli_query($xcon, $sql13) or die("No se pueden seleccionar los puntajes. Error: ".mysqli_error($xcon));
								if ($puntajes = mysqli_fetch_array($r13, MYSQLI_ASSOC)) $puntos = $puntajes["Puntos"] + $puntosGanados; 
								
								//Actualizar los puntos totales
								$sql14 = "UPDATE puntajes SET Puntos = ".$puntos." WHERE IdParticipante = '".$participantes["IdParticipante"]."'";
								$r14 = mysqli_query($xcon, $sql14) or die("No se pueden actualizar los puntajes. Error: ".mysqli_error($xcon));
								//
								$puntos = 0;
							}//Fin if resultados
						}//Fin if partido con resultado modificado
					}//Fin while participantes
				}//Fin If condicionador de ingreso de resultados 
			}//Fin If Estado Jugado
		}//Fin If SELECT partidos
	}//Fin If cambio de marcadores, goles y puntos en los equipos. Condición $partido.
}

function mostrarEncuentros($xcon, $hoy) {
	$sql1 = "SELECT * FROM partidosqatar2022 WHERE Fecha = '2022-11-22'";	
	#$sql1 = "SELECT * FROM Partidos WHERE Fecha = '".$hoy."'";
	$r1 = mysqli_query($xcon, $sql1);
	while ($datos1 = mysqli_fetch_array($r1, MYSQLI_ASSOC))
	{
?>
		<form name="<?php echo $datos1["idPartido"]; ?>" method="post" action="ingresoresultados.php?p=<?php echo $datos1["idPartido"]; ?>">
        	<table border="0">
				<tr><td colspan="3" align="center"><?php echo $datos1["lugar"]." - ".$datos1["hora"]; ?></td></tr>
				<tr>
					<td style="font-family : Arial; font-size : 8pt; text-align : center;" width="87px"><?php echo $datos1["Equipo1"]; ?></td>
					<td>&nbsp;</td>
					<td style="font-family : Arial; font-size : 8pt; text-align : center;" width="87px"><?php echo $datos1["Equipo2"]; ?></td>
				</tr>
                <tr>
<?php
		$sql2 = "SELECT * FROM equipos WHERE Id = '".$datos1["idEquipo1"]."'";
		$r2 = mysqli_query($xcon, $sql2);
		if ($datos2 = mysqli_fetch_array($r2, MYSQLI_ASSOC))
		{
			echo "<td align='center'><img src='".$datos2["Bandera"]."' width='70%'></td>";
		}
?>
           			<td><input type='text' name="<?php echo "R1-".$datos1["idPartido"]; ?>" placeholder="-" id="<?php echo "R1-".$datos1["idPartido"]; ?>" size="2" aria-describedby='sizing-addon1' style="text-align : center;" <?php if($datos1["estado"] == "Jugado") echo "value='".$datos1["golesEquipo1"]."' Disabled"; else echo "required"; ?>> - <input type='text' name="<?php echo "R2-".$datos1["idPartido"]; ?>" placeholder="-" id="<?php echo "R2-".$datos1["idPartido"]; ?>" size="2" aria-describedby='sizing-addon1' style="text-align : center;" <?php if($datos1["estado"] == "Jugado") echo "value='".$datos1["golesEquipo2"]."' Disabled"; else echo "required"; ?>></td>

<?php
		$sql3 = "SELECT * FROM equipos WHERE Id = '".$datos1["idEquipo2"]."'";
		$r3 = mysqli_query($xcon, $sql3);
		if ($datos3 = mysqli_fetch_array($r3, MYSQLI_ASSOC))
		{
			echo "<td align='center'><img src='".$datos3["Bandera"]."' width='70%'></td>";
		}
?>				
				</tr>
				<tr><td colspan="3"<?php if($datos1["estado"] != "Jugado") echo "align='center'><input type='submit' name='guardar' value='Guardar'></td></tr>
                							<tr><td colspan='3'>&nbsp;</td></tr>"; else echo "<tr><td colspan='3'>&nbsp;</td></tr>"; ?> 
            </table>
        </form>
<?php
	}//Fin while $sql1
}

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