<?php
	session_start();
	//
	if (!isset($_GET['p'])) $_SESSION['p'] = 0;
	else $_SESSION['p'] = $_GET['p'];
	if ($_SESSION['p'] == 0) session_destroy();
	if (!isset($_GET['m'])) $_SESSION['m'] = 0;
	else $_SESSION['m'] = $_GET['m'];
	if (!isset($_SESSION['i'])) $_SESSION['i'] = 0;
	require_once 'datos/determ.php';
?>
<!DOCTYPE html Content-type: text/html; charset=utf-8>
<html>
	<head>
		<title>Acierta en Qatar 2022</title>
		<script language="JavaScript" src="datos/date.js"></script> 
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<!-- vinculo a bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- Temas-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="datos/estilo.css">
	</head>
	<body onLoad="Reloj()">
		<div id="Contenedor">
			<div class="Icon"><img src="images/LogoQatar.png"></span></div>
			<center><h1>Acierta en Qatar 2022</h1></center>
			<form name="form_reloj"> 
				<input type="text" class="form-control" name="reloj" style="background-color : F3EDED; color : Black; font-family : Arial; font-size : 10pt; text-align : center;" aria-describedby="sizing-addon1" disabled> 
			</form>
			<br>
			<?php
				if(!empty($_POST['Usr']) && !empty($_POST['pwd'])){
					$iniciar=new Login($_POST['Usr'],$_POST['pwd']);
					$iniciar->Ingresar();
					echo $iniciar->MostrarMsg();
				}
			/*
			require_once 'datos/config.php';
			$xcon = conectarBd();
			$sql = $xcon->query("SELECT * FROM participantes WHERE IdParticipante='94533535'");
			if($res = $sql->fetch()) {
				print $res['Nombre'];
			}
			$xcon = Null;
			*/
			?>
			<div class="ContentForm">
				<form action="" method="post" name="FormEntrar">
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" name="Usr" placeholder="Tu usuario" id="Usr" aria-describedby="sizing-addon1" required>
					</div>
					<br>
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" name="pwd" class="form-control" placeholder="******" aria-describedby="sizing-addon1" required>
					</div>
					<br>
					<button class="btn btn-lg btn-primary btn-block btn-signin" id="IngresoLog" type="submit">Entrar</button>
					<center>____________________________________________<br>
					<font face="Trebuchet MS, Arial, Helvetica, sans-serif" size="2">Dromasio &copy 2022</font></center>
				</form>
			</div>
		</div>
		<?php $_SESSION['p'] = 1; ?>
	</body>
	<!-- vinculando a biblioteca Jquery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Biblioteca javascript de bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</html>		