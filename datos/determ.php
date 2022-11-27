<?php 
session_start();
require_once 'PasswordHash.Class.php';
require_once 'config.php';

class Login{

	//campos que alamcenan los valores 
	private $Usr_       ="";
	private $Contrasena_ ="";
	private $Mensaje     ="";
	#private $Nombre_usr  ="";
    /**
     * [constructor recibe argumentos]
     * @param [type] $Usr    [ingresar usuario]
     * @param [type] $Pasword [Ingresar contraseña]
     */
	function __construct($Usr,$Pwd){
		$this->Usr_=$Usr;
		$this->Contrasena_=$Pwd;
		$this->Nombre_usr="";
	}

	/**
	 * [Metdo devuelve true o false para ingresar
	 * a la sesccion de pagina de administracion
	 * ]
	 */
	public function Ingresar(){
		//determinamos cada uno de los
		//metodos devueltos
		if($this->ValidateUser()==false) $this->Mensaje=$this->Mensaje;	
		else{
			if($this->Pasword_usr()==false) $this->Mensaje=$this->Mensaje;	
			else{
				
				/*
				# Conficugración de la sesione encontrada en stackoverflow
				if($_SERVER['SERVER_PORT'] !== 443 && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
					header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
					exit;
				}
				
				# Código generado aujtomáticamente por copilot
				if (isset($_SESSION['i']) && $_SESSION['i'] == 1) {
					$acceso=new Login($_SESSION['Usr'],$_SESSION['pwd']);
					$acceso->Acceso();
					echo $acceso->MostrarMsg();
				}
				*/
				//por lo es correcto el logeo realizamos la redireccion
				$_SESSION['i'] = $this->Usr_;
				#$_COOKIE['i'] = $this->Usr_;
				if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) $uri = 'https://';
				else $url = 'http://';
				$url .= $_SERVER['HTTP_HOST'];
				//Aqui modificar si el pag de aministracion esta 
				//en un subdirectorio
				// "<script type=\"text/javascript\">
				// window.location=\"".$uri."/wp-admin/admin.php\";
				// </script>";
				/*
				# Trabajar con esta linea en heroku
				echo    "<script type=\"text/javascript\">
						window.location=\"".$url."/presentacion.php\";
						</script>";
				*/
				# Trabajar con esta línea de forma local
				echo 	"<script type=\"text/javascript\">
						window.location=\"".$uri."/mundial/presentacion.php\";
						</script>";
				
			} 
		}
	}

	private function ValidateUser(){
		$retornar=false;
		$numfilter =filter_var($this->Usr_,FILTER_VALIDATE_INT);  //filtramos el numero de usuario
		$numfilter = "$numfilter";
		if(preg_match("/[0-9]/", $numfilter )==true){
			$xcon = conectarBd(); 	
			$sql = $xcon->query("SELECT * FROM participantes WHERE IdParticipante='".$numfilter."';");
			//$r = $xcon->query($sql);
			if($r = $sql->fetch()) $retornar=true;
			else $this->Mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><strong>Error: </strong>El usuario ingresado no se encuentra registrado.</div>';
		}else $this->Mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button> <strong> Error: </strong>Usuario y/o clave incorrectos.</div>';
		$xcon = null;
		return $retornar;
	}

	private function Pasword_usr(){
		$retornar = false;
		$contra = filter_var($this->Contrasena_,FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP);
		//$contra = filter_var($this->Contrasena_, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP);
		if($contra=="") { $this->Mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button> <strong> Error:</strong> Escriba su clave. </div>'; }
		else{
			//$Clave = new PasswordHash(8, FALSE);
			$xcon = conectarBd(); 	
			$sql = $xcon->query("SELECT * FROM participantes WHERE IdParticipante='".$this->Usr_."'");
			if($res = $sql->fetch()) {
				//se obtiene el arreglo de la base de datos
				$Hashing = $res['IdParticipante'];
				//Realizamos el comparacion del pasword con la instrccion if
				if(CheckPasword($contra, $Hashing)){
					$this->Nombre_usr = $res['Nombre'];
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
		$xcon = null;
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




