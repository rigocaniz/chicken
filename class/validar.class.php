<?php
/**
* VALIDAR
*/
class Validar
{
	private $error   = FALSE;
	private $mensaje = '';
	private $tiempo  = 6;

	// VALIDAR NOMBRE
	function validarNombre( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;

		if( !(strlen( $valor ) >= 3) ):
			$warning = TRUE;
			$this->mensaje = 'Nombre inválido, verifique un minímo de 3 caracteres.';
		endif;

		if( $warning AND $required ):
			$valor = $default;
			$this->error = TRUE;
		endif;
	}


	// VALIDAR DIRECCION
	function validarDireccion( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;

		if( !(strlen( $valor ) >= 10) ):
			$warning = TRUE;
			$this->mensaje = 'Dirección inválida, verifique que tenga un mínimo de 10 caracteres.';
		endif;

		if( $warning AND $required ):
			$valor = $default;
			$this->error = TRUE;
		endif;
	}

	
	// VALIDAR CUI
	function validarCui( $valor = 0, $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;

		if( !(strlen( (int)$valor ) == 13) ):
			$warning = TRUE;
			$this->mensaje = 'No. de CUI inválido, verifique que tenga 13 dígitos.';
		endif;

		if( $warning AND $required ):
			$valor = $default;
			$this->error = TRUE;
		endif;
	}


	// VALIDAR NIT
	function validarNit( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;

		if( !(strlen( (int)$valor ) >= 8) ):
			$warning = TRUE;
			$this->mensaje = 'No. de NIT inválido, verifique.';
			$this->tiempo  = 4;
		endif;

		if( $warning AND $required ):
			$valor = $default;
			$this->error = TRUE;
		endif;
	}


	// VALIDAR NUMERO
	function validarNumero( $valor = '', $default = NULL, $required = TRUE, $min = 0, $max = 0 )
	{
		if( $this->error )
			return $valor;
		
		$warning = FALSE;
		$valor   = (int)$valor;

		if ( !filter_var( $valor, FILTER_VALIDATE_INT ) AND $required ):
			$warning = TRUE;
    		$this->mensaje = 'El número ingresado es inválido, verifique.';
    		$this->tiempo  = 4;
    	elseif( $valor < $min ):
    		$warning = TRUE;
    		$this->mensaje = "El número ingresado debe ser mayor a {$min}, verifique.";
    		$this->tiempo  = 5;
		endif;

		if( $warning AND $required ):
			$valor = $default;
			$this->error = TRUE;
		endif;

		return $valor;
	}

	
	// VALIDAR TELEFONO
	function validarTelefono( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$valor = (int)$valor;

		if( !(strlen( $valor ) == 8) AND $required ):
			$warning = TRUE;
    		$this->mensaje = 'No. de teléfono inválid, verifique que tenga 8 dígitos.';
		endif;

		if( $warning AND $required ):
			$valor = $default;
			$this->error = TRUE;
		endif;

		return $valor;
	}

	
	// VALIDAR CORREO
	function validarCorreo( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;

		if ( !filter_var( $valor, FILTER_VALIDATE_EMAIL ) AND $required ):
    		$warning = TRUE;
    		$this->mensaje = 'Correo electronico invalido, verifique.';
    		$this->tiempo  = 4;
		endif;

		if( $warning AND $required ):
			$valor = $default;
			$this->error = TRUE;
		endif;

		return $valor;
	}

	
	// VALIDAR ENTERO
	function validarCantidad( $valor = 0, $default = NULL, $required = TRUE, $minimo = 0, $maximo = 50000,  $msj = '' )
	{
		if( $this->error )
			return $valor;
		
		$warning = FALSE;
		$valor   = (double)$valor;

		if ( !is_numeric( $valor ) ):
			$warning       = TRUE;
			$this->mensaje = "El valor ingresado en {$msj} no es númerico, verifique.";
		elseif( $valor < $minimo ):
			$warning       = TRUE;
			$this->mensaje = "El valor ingresado en {$msj} no puede ser menor a {$minimo}, verifique.";
		elseif( $valor > $maximo ):
			$warning       = TRUE;
			$this->mensaje = "El valor ingresado en {$msj} no puede ser mayor a {$maximo}, verifique.";
		endif;

		if( $warning AND $required ):
			$valor       = $default;
			$this->error = TRUE;
		endif;

		return $valor;
	}

	
	// VALIDAR ENTERO
	function validarEntero( $valor = 0, $default = NULL, $required = TRUE, $msj = '' )
	{
		if( $this->error )
			return $valor;
		
		$warning = FALSE;
		$valor   = (int)$valor;

		if ( !filter_var( $valor, FILTER_VALIDATE_INT ) AND $required ):
			$warning       = TRUE;
			$this->mensaje = $msj;
		endif;

		if( $warning AND $required ):
			$valor       = $default;
			$this->error = TRUE;
		endif;

		return $valor;
	}

	
	// VALIDAR TEXTO
	function validarTexto( $valor = 0, $default = NULL, $required = TRUE, $minimo = 1, $maximo = 350, $msj = '' )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;
		$valor   = (string)$valor;

		if( !strlen( $valor ) AND $required ):
			$warning = TRUE;
			$this->mensaje = "No ha ingresado ningún texto en {$msj}, verifique.";
			$this->tiempo  = 5;
		elseif( strlen( $valor ) < $minimo ):
			$warning = TRUE;
			$this->mensaje = "El contenido ingresado en {$msj} no puede ser menor a {$minimo} caracteres, verifique.";
		elseif( strlen( $valor ) > $maximo ):
			$warning = TRUE;
			$this->mensaje = "El contenido ingresado en {$msj} no puede ser mayor a {$maximo} caracteres, verifique.";
		endif;

		if( $warning AND $required ):
			$valor       = $default;
			$this->error = TRUE;
		endif;

		return $valor;
	}


	// OBTENER ERROR
	function getIsError()
	{
		return $this->error;
	}

	function getTiempo()
	{
		return $this->tiempo;
	}

	// OBTENER MENSAJE
	function getMsj()
	{
		return $this->mensaje;
	}

}
?>