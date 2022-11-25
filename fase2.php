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
	include_once('datos/functions.php'); 
	// Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('UTC');
    $eqOctavos = array("1A", "1B", "2B", "2A", "1C", "1D", "2D", "2C", "1E", "1F", "2F", "2E", "1G", "1H", "2H", "2G");
    $eqCuartos = array("");	
    $nPartido = array(49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64);
?>
<!DOCTYPE html Content-type: text/html; charset=utf-8>
<html lang="es">
<?php
    headers2(); 
	$sql2 = "SELECT * FROM participantes WHERE IdParticipante = '".$_SESSION["i"]."'";
	$r2 = mysqli_query($xcon, $sql2) or die("No se encontr&oacute; el usuario. Error: ".mysqli_error($xcon));
	if ($participantes = mysqli_fetch_array($r2, MYSQLI_ASSOC)) echo "Bienvenido(a): ".$participantes["Nombre"]."<br>Aqu&iacute; encuentras la segunda fase de la copa mundial:<br><br>";
?>
		<strong>Fase Definitiva</strong><br><br>		
        <table border="0">
        	<tr style="font-size:70%;">
            	<td colspan="2" align="center">Octavos</td>
                <td colspan="2" align="center">Cuartos</td>
                <td colspan="2" align="center">Semifinal</td>
                <td width="230" align="center">&nbsp;</td>
                <td colspan="2" align="center">Semifinal</td>
                <td colspan="2" align="center">Cuartos</td>
                <td colspan="2" align="center">Octavos</td>
            </tr>
            <tr><td height="10" colspan="13">&nbsp;</td></tr>
            <tr>
            	<td height="40" width="100" align="center"><?php echo impOctavos($xcon, $nPartido[0], $eqOctavos[0]); ?></td>
                <td width="18" rowspan="3">
                	<img src='images/lines2l.png' width='8px'>
                </td>
                <td rowspan="3" width="100" align="center"><?php echo impCuartos($xcon, $nPartido[8], "I"); ?></td>
                <td width="12" rowspan="7"><img src='images/lines2l.png' alt="" width='12px' height='250px'></td>
                <td rowspan="7" width="100" align="center"><?php echo impSemifinal($xcon, $nPartido[12], "Q1"); ?></td>
                <td width="17" rowspan="15">
                	<img src='images/lines2l.png' width='12px' height='448px'>
                </td>
                <td width="230" align="center">&nbsp;</td>
                <td width="12" rowspan="15">
                	<img src='images/lines2r.png' width='12px' height='448px'>
                </td>
                <td rowspan="7" width="100" align="center"><?php echo impSemifinal($xcon, $nPartido[13], "Q2"); ?></td>
                <td width="12" rowspan="7"><img src='images/lines2r.png' alt="" width='12px' height='250px'></td>
                <td rowspan="3" width="100" align="center"><?php echo impCuartos($xcon, $nPartido[10], "J"); ?></td>
                <td width="18" rowspan="3"><img src='images/lines2r.png' width='8px'></td>
                <td width="100" align="center"><?php echo impOctavos($xcon, $nPartido[2], $eqOctavos[1]); ?></td>
            </tr>
            <tr style="font-size:70%;">
                <td height="10">&nbsp;</td>
                <td align="center">Campe&oacute;n</td>
                <td>&nbsp;</td>
            </tr>
            <tr>    
               <td height="40" align="center"><?php echo impOctavos($xcon, $nPartido[0], $eqOctavos[2]); ?></td>
               <td align="center" rowspan="2"><font size="1%">
					<?php
						$sql5 = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Posicion = 1";
						$r5 = mysqli_query($xcon, $sql5) or die("No se encontr&oacute; equipo WS1 en Final. Error: ".mysqli_error($xcon));
						if ($equipos_octavos = mysqli_fetch_array($r5, MYSQLI_ASSOC)) {
							echo $equipos_octavos["Nombre"]."<br>
								<img src='".$equipos_octavos["Bandera"]."' width='40%'>";
						}
					?>
					</font>
               </td>
               <td align="center"><?php echo impOctavos($xcon, $nPartido[2], $eqOctavos[3]); ?></td>
            </tr>    
            <tr>    
                <td height="10">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>    
            <tr>    
            	<td height="40" align="center"><?php echo impOctavos($xcon, $nPartido[1], $eqOctavos[4]); ?></td>
            	<td rowspan="3"><img src='images/lines2l.png' alt="" width='8px'></td>
                <td rowspan="3" align="center"><?php echo impCuartos($xcon, $nPartido[8], "K"); ?></td>
                <td align="center">&nbsp;</td>
                <td rowspan="3" align="center"><?php echo impCuartos($xcon, $nPartido[10], "L"); ?></td>
                <td rowspan="3"><img src='images/lines2r.png' alt="" width='8px'></td>
                <td align="center"><?php echo impOctavos($xcon, $nPartido[3], $eqOctavos[5]); ?></td>
            </tr>    
            <tr style="font-size:70%;">    
                <td height="10">&nbsp;</td>
                <td align="center">Final</td>
                <td>&nbsp;</td>
            </tr>    
            <tr>    
                <td height="40" align="center"><?php echo impOctavos($xcon, $nPartido[1], $eqOctavos[6]); ?></td>
            	<td rowspan="3" align="center">
                	<table border="0">
                    	<tr>
                        	<td width="100px" align="left"><?php echo impPartidoTer($xcon, $nPartido[15], "WS1"); ?></td>
                            <td align="center">
                            	<font size='5px'> - </font>
                            </td>
                        	<td width="100px" align="right"><?php echo impPartidoTer($xcon, $nPartido[15], "WS2"); ?></td>
                        </tr>
                    </table> 
                </td>
                <td align="center"><?php echo impOctavos($xcon, $nPartido[3], $eqOctavos[7]); ?></td>
            </tr>    
            <tr>    
                <td height="10">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td height="40" align="center"><?php echo impOctavos($xcon, $nPartido[4], $eqOctavos[8]); ?></td>
            	<td rowspan="3"><img src='images/lines2l.png' width='8px'></td>
                <td rowspan="3" align="center"><?php echo impCuartos($xcon, $nPartido[9], "M"); ?></td>
                <td rowspan="7">
                	<img src='images/lines2l.png' alt="" width='12px' height='250px'>
                </td>
                <td rowspan="7" align="center"><?php echo impSemifinal($xcon, $nPartido[12], "Q3"); ?></td>
                <td rowspan="7" align="center"><?php echo impSemifinal($xcon, $nPartido[13], "Q4"); ?></td>
                <td rowspan="7"><img src='images/lines2r.png' alt="" width='12px' height='250px'></td>
              	<td rowspan="3" align="center"><?php echo impCuartos($xcon, $nPartido[11], "N"); ?></td>
                <td rowspan="3"><img src='images/lines2r.png' width='8px'></td>
              	<td align="center"><?php echo impOctavos($xcon, $nPartido[6], $eqOctavos[9]); ?></td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              	<td height="40" align="center"><?php echo impOctavos($xcon, $nPartido[4], $eqOctavos[10]); ?></td>
            	<td>&nbsp;</td>
              	<td align="center"><?php echo impOctavos($xcon, $nPartido[6], $eqOctavos[11]); ?></td>
            </tr>
            <tr style="font-size:70%;">
              <td height="10">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="center">Tercero y cuarto</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td  height="40" align="center"><?php echo impOctavos($xcon, $nPartido[5], $eqOctavos[12]); ?></td>
              	<td rowspan="3">
                	<img src='images/lines2l.png' alt="" width='8px'>
                </td>
              	<td rowspan="3" align="center"><?php echo impCuartos($xcon, $nPartido[9], "O"); ?></td>
                <td rowspan="3" align="center">
                	<table border="0">
              	  		<tr>
              	    		<td width="100px" align="left"><?php echo impPartidoTer($xcon, $nPartido[14], "LS1"); ?></td>
							<td align="center">
                                <font size='5px'> - </font>
                            </td>
              	    		<td width="100px" align="right"><?php echo impPartidoTer($xcon, $nPartido[14], "LS2"); ?></td>
           	      		</tr>
           	    	</table>
                </td>
              	<td rowspan="3" align="center"><?php echo impCuartos($xcon, $nPartido[11], "P"); ?></td>
                <td rowspan="3"><img src='images/lines2r.png' alt="" width='8px'></td>
           	  	<td align="center"><?php echo impOctavos($xcon, $nPartido[7], $eqOctavos[13]); ?></td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              	<td height="40" align="center"><?php echo impOctavos($xcon, $nPartido[5], $eqOctavos[14]); ?></td>
            	<td align="center"><?php echo impOctavos($xcon, $nPartido[7], $eqOctavos[15]); ?></td>
            </tr>    
            <tr style="font-size:70%;">    
                <?php for ($i=0; $i < 6; $i++) echo "<td>&nbsp;</td>"; ?>
                <td align="center">Tercero</td>
                <?php for ($i=0; $i < 6; $i++) echo "<td>&nbsp;</td>"; ?>
            </tr>    
            <tr>    
               <?php for ($i=0; $i < 6; $i++) echo "<td>&nbsp;</td>"; ?>
               <td align="center"><font size="1%">
					<?php 
						$sql5 = "SELECT Nombre, Bandera FROM equipos_octavos WHERE Posicion = 3";
						$r5 = mysqli_query($xcon, $sql5) or die("No se encontr&oacute; equipo LS1 en Final. Error: ".mysqli_error($xcon));
						if ($equipos_octavos = mysqli_fetch_array($r5, MYSQLI_ASSOC)) 
						{
							echo $equipos_octavos["Nombre"]."<br>
								<img src='".$equipos_octavos["Bandera"]."' width='40%'>";
						}
					?>
					</font>
               </td>
               <?php for ($i=0; $i < 6; $i++) echo "<td>&nbsp;</td>"; ?>
            </tr>    
        </table> 
        <br>      
<?php   
    footers();
    mysqli_close($xcon);
?>  
</html>	