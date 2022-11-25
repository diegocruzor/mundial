<html>
    <body>
<?php
//Este auxiliar es para actualizar los nombres de los equipos
//en los octavos de final para todos los participantes

	$xcon = mysqli_connect('localhost', 'admin', '123456', 'mundial');
	if (!$xcon) {
    die('Error de Conexión (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
	}
	//
	//Desde el n�mero de partido a actualizar despu�s de haber actualizado
	//la tabla partidos
	$partido = 49;
	//N�mero de partido
	while ($partido <= 64)
	{
		//Seleccionar todos los participantes
		$sql1 = "SELECT * FROM participantes";
		$r1 = mysql_query($sql1) or die ("No se puede seleccionar participantes. Error: ".mysql_error());
		//echo $partido."<br>";		
		while ($participantes = mysql_fetch_array($r1))
		{
			//Seleccionar los partidos desde los que se va a actualizar nombres
			//y resultados para cada participante
			$sql2 = "SELECT * FROM partidos WHERE NoPartido = ".$partido;
			$r2 = mysql_query($sql2) or die ("No se puede seleccionar partidos. Error: ".mysql_error());
			//echo $sql2."<br>";
			if ($partidos = mysql_fetch_array($r2))
			{
				//Actualizar equipos y marcadores en resultados
				$sql3 = "UPDATE resultados 
					SET	Equipo_1 = '".$partidos["Equipo_1"]."',
						Equipo_2 = '".$partidos["Equipo_2"]."', 
						Resultado_1 = 0,
						Resultado_2 = 0
					WHERE IdParticipante = '".$participantes["IdParticipante"]."'
						AND NoPartido = ".$partido;
				//echo $sql3."<br>";
				$r3 = mysql_query($sql3) or die ("No se puede modificar el registro en resultados. Error: ".mysql_error());
			}//Fin if partidos
		}//Fin while participantes
		$partido++;
	}//Fin while partido
        //	
        mysqli_close($xcon);

?>
    </body>
</html>