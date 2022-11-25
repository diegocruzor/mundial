<?php 
class Datos_conexion {
	# Setters: Sets the database access information as constants (in digitalocean): 
	private $host_="db-mysql-nyc1-78181-do-user-12327901-0.b.db.ondigitalocean.com";
	private $usuario_="doadmin";
	private $pasword_="AVNS_2H3VQuYrisskEq6HqSk";
	private $Db_="mundialqatar2022";
	private $puerto_="25060";
	# Getters
	public function host(){
		return $this->host_;
	}
	public function usuario(){
		return $this->usuario_;
	}
	public function pasword(){
		return $this->pasword_;
	}
	public function DB(){
		return $this->Db_;
	}
	public function puerto(){
		return $this->puerto_;
	}

}

function conectarBd() {
	$confi=new Datos_conexion();
	$xcon = mysqli_connect($confi->host(),$confi->usuario(),$confi->pasword(),$confi->DB(),$confi->puerto());
	if(!$xcon) $this->Mensaje='<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button> <strong> Error:</strong> Servidor de datos no encontrado, vuelva a intentar mas tarde.</div>';
	return ($xcon);
}

?>