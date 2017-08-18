<?php
/**
* Sesiones
*/
class Sesion
{
	
	private $sesion = null;


	function destruirSesion()
	{
		session_start();
		session_destroy();
		header("Location: login.php");
	}

	// SETEAR VARIABLE
	function setVariable( $var, $valor )
	{
		return $_SESSION[ $var ] = $valor;
	}


	// OBTENER VARIABEL
	private function getVariable( $var )
	{
		return isset( $_SESSION[ $var ] ) AND $_SESSION[ $var ];
	}
	

	function getUsuario()
	{
		return $this->getVariable( 'usuario' );
	}


	function getIdNivel()
	{
		return (int)$this->getVariable( 'idNivel' );
	}


	function getIdPerfil()
	{
		return (int)$this->getVariable( 'idPerfil' );
	}

}

$sesion = new Sesion();

?>