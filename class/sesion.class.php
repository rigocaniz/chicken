<?php
/**
* Sesiones
*/
class Sesion
{
	
	function __construct()
	{
		$this->iniciarSesion();
	}

	function destruirSesion()
	{
		session_start();
		session_destroy();
		header("Location: login.php");
	}

	function iniciarSesion()
	{
		if( !isset( $_SESSION ) )
			session_start();
	}

	// SETEAR VARIABLE
	function setVariable( $var, $valor )
	{
		return $_SESSION[ $var ] = $valor;
	}

	// OBTENER VARIABEL
	private function getVariable( $var )
	{
		return isset( $_SESSION[ $var ] ) ? $_SESSION[ $var ] : NULL;
	}

	function getUsuario()
	{
		return $this->getVariable( 'usuario' );
	}

	function getNombre()
	{
		return $this->getVariable( 'nombre' );
	}

	function getIdPerfil()
	{
		return (int)$this->getVariable( 'idPerfil' );
	}

	function getNombreCorto()
	{
		return $this->getVariable( 'nombreCorto' );
	}

	function getCodigoUsuario()
	{
		return (int)$this->getVariable( 'codigoUsuario' );
	}

	function getAccesoModulo( $idModulo )
	{
		$modulo = 'modulo_' . (int)$idModulo;
		return (int)$this->getVariable( $modulo );
	}

}

$sesion = new Sesion();

?>