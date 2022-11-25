<?php 
session_start();
require_once 'PasswordHash.Class.php';
require_once 'config.php';

class Login{

	//campos que alamcenan los valores 
	private $Usr_       ="";
	private $Contrasena_ ="";
	private $Mensaje     ="";
	private $Nombre_usr  ="";
    /**
     * [constructor recibe argumentos]
     * @param [type] $Usr    [ingresar usuario]
     * @param [type] $Pasword [Ingresar contraseña]
     */
	function __construct($Usr,$Pwd){
		$this->Usr_=$Usr;
		$this->Contrasena_=$Pwd;
	}

/**
 * [Metdo devuelve true o false para ingresar
 * a la sesccion de pagina de administracion
 * ]
 */
public function Ingresar(){
    //determinamos cada uno de los
    //metodos devueltos
	if($this->ValidarUser()==false) $this->Mensaje=$this->Mensaje;	
	else{
		if($this->Pasword_usr()==false) $this->Mensaje=$this->Mensaje;	
		else{
     		//por lo es correcto el logeo realizamos la redireccion
			if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) $uri = 'https://';
			else $uri = 'http://';
			$uri .= $_SERVER['HTTP_HOST'];
			$_SESSION['i'] = $this->Contrasena_;
				//Aqui modificar si el pag de aministracion esta 
		    //en un subdirectorio
		    // "<script type=\"text/javascript\">
			// window.location=\"".$uri."/wp-admin/admin.php\";
			// </script>";
			echo    "<script type=\"text/javascript\">
					window.location=\"".$uri."/presentacion.php\";
					</script>";
			/*
						Así estaba antes de subir a Heroku
			echo 	"<script type=\"text/javascript\">
					window.location=\"".$uri."/mundial/presentacion.php\";
					</script>";
			*/
		} 
	}
}

private function ValidarUser(){
	$retornar=false;
	$numfilter =filter_var($this->Usr_,FILTER_VALIDATE_INT);  //filtramos el numero de usuario
	$numfilter = "$numfilter";
	if(preg_match("/[0-9]/", $numfilter )==true){
		$xcon = conectarBd(); 	
		$sql = "SELECT * FROM participantes WHERE IdParticipante='".$numfilter."';";
	 	$r = mysqli_query($xcon, $sql);
	 	if($res = mysqli_fetch_row($r)) $retornar=true;
	    else $this->Mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><strong>Error: </strong>El usuario ingresado no se encuentra registrado.</div>';
	}else $this->Mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button> <strong> Error: </strong>Usuario y/o clave incorrectos.</div>';
	return $retornar;
}

private function Pasword_usr(){
	$retornar = false;
	$contra = filter_var($this->Contrasena_, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP);
	if($contra=="") { $this->Mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button> <strong> Error:</strong> Escriba su clave. </div>'; }
	else{
		$Clave = new PasswordHash(8, FALSE);
        $xcon = conectarBd(); 	
		$sql = "SELECT * FROM participantes WHERE IdParticipante='".$this->Usr_."'";
	 	$r = mysqli_query($xcon, $sql);
	 	if($res = mysqli_fetch_row($r)) {
	        //se obtiene el arreglo de la base de datos
	        $Hashing = $res[0];
            //Realizamos el comparacion del pasword con la instrccion if
	        if(CheckPasword($contra, $Hashing)){
	            $this->Nombre_usr = $res[0];
	            //Recuperando el IP del usuario atravez del metodo IPuser()  
	            $IpUsr = $this->IPuser();
	           	//Recuperando la hora en el que ingreso
	           	$hora = time();
	            $this->Contrasena_ = $contra;
	            $retornar = true;
	        }else {
	        	$this->Mensaje ='<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button> <strong> Error:</strong> Usuario y/o clave incorrectos. </div>';
	            $retornar = false; //El pwd ingresado no es correcto
	        }
	    }
	    else $this->Mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><strong>Error: </strong>El usuario ingresado no se encuentra registrado.</div>';
	}
 	return $retornar; 
}
/**
 * Returna el IP de usuario
 * @return [string] [devuel la io del usuario]
 */
private function IPuser() {
	$returnar ="";
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
     $returnar=$_SERVER['HTTP_X_FORWARDED_FOR'];}
if (!empty($_SERVER['HTTP_CLIENT_IP'])){
     $returnar=$_SERVER['HTTP_CLIENT_IP'];}
if(!empty($_SERVER['REMOTE_ADDR'])){
	 $returnar=$_SERVER['REMOTE_ADDR'];}
return $returnar;
}

public function MostrarMsg(){
	return $this->Mensaje;
}


}








 ?>




