<?php
/**
* Sesiones
*/
class Sesion
{
	
	private $sesion = null;

	function __construct()
	{
		/*
		if( is_null( $this->sesion ) )
			$this->iniciarSesion();
			*/
	}

	private function iniciarSesion()
	{
		session_start();
	}


	function destruirSesion()
	{
		session_start();
		session_destroy();
		header("Location: login.php");
	}

	
	function setVariable( $var, $valor )
	{
		return $_SESSION[ $var ] = $valor;
	}


	private function getVariable( $var )
	{
		return $_SESSION[ $var ];
	}
	

	function getUsuario()
	{
		return $this->getVariable( 'usuario' );
	}

}

$sesion = new Sesion();

?>