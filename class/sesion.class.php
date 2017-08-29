<?php
/**
* Sesiones
*/
class Sesion
{
	
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
		return @$_SESSION[ $var ];
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

}

$sesion = new Sesion();

?>