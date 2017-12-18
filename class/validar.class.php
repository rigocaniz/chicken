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
		endif;

		if( $warning AND $required ):
			$this->mensaje = 'Nombre inválido, verifique un minímo de 3 caracteres.';
			$this->error   = TRUE;
		
		elseif( !$required AND $warning ):
			$valor = $default;

		endif;

		return $valor;
	}


	// VALIDAR DIRECCION
	function validarDireccion( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;

		if( !(strlen( $valor ) >= 8) AND ( $valor != 'CIUDAD' ) ):
			$warning = TRUE;
		endif;


		if( $warning AND $required ):
			$this->error   = TRUE;
			$this->mensaje = 'Dirección inválida, verifique que tenga un mínimo de 8 caracteres.';
		
		elseif( !$required AND $warning ):
			$valor = $default;

		endif;

		return $valor;
	}

	
	// VALIDAR CUI
	function validarCui( $valor = 0, $default = NULL, $required = FALSE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;

		if( strlen( $valor ) AND !(preg_match('/^[0-9-\s]{13,15}$/', $valor ) AND strlen( $valor) >= 13 AND strlen( $valor) <= 15) ):
			$warning = TRUE;
		endif;

		if( $warning AND $required ):
			$this->error   = TRUE;
			$this->mensaje = 'No. de CUI inválido, verifique que tenga 13 dígitos.';

		elseif( !$required AND $warning ):
			$valor = $default;

		endif;

		return $valor;
	}


	// VALIDAR NIT
	function validarNit( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;
		if( !(preg_match('/^[0-9-\s]{5,10}$/', $valor ) AND strlen( $valor ) >= 5  AND strlen( $valor ) <= 10) AND ( $valor != 'CF' AND $valor != 'C/F' ) ):
			$warning = TRUE;
		endif;

		if( $warning AND $required ):
			$this->error   = TRUE;
			$this->mensaje = 'No. de NIT inválido, verifique que tenga entre 5 y 10 dígitos.';
			$this->tiempo  = 4;

		elseif( !$required AND $warning ):
			$valor = $default;
		endif;

		return $valor;
	}


	// VALIDAR NUMERO
	function validarNumero( $valor = '', $default = NULL, $required = TRUE, $min = 0, $max = 0 )
	{
		if( $this->error )
			return $valor;
		
		$warning = FALSE;
		$valor   = (int)$valor;
		$msj     = "";
		$tiempo  = 3;

		if ( !filter_var( $valor, FILTER_VALIDATE_INT ) AND $required ):
			$warning = TRUE;
			$msj     = 'El número ingresado es inválido, verifique.';
			$tiempo  = 4;
    	elseif( $valor < $min ):
    		$warning = TRUE;
    		$msj     = "El número ingresado debe ser mayor a {$min}, verifique.";
    		$tiempo  = 5;
		endif;

		if( $warning AND $required ):
			$this->error   = TRUE;
			$this->mensaje = $msj;
			$this->tiempo  = $tiempo;

		elseif( !$required AND $warning ):
			$valor = $default;

		endif;

		return $valor;
	}

	
	// VALIDAR TELEFONO
	function validarTelefono( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;
		$valor   = (int)$valor;

		if( !(strlen( (string)$valor ) == 8) )
			$warning = TRUE;

		if( $warning AND !$required )
			$valor = $default;

		if( $warning AND $required ):
			$this->error = TRUE;
    		$this->mensaje = 'No. de teléfono inválido, verifique que tenga 8 dígitos.';
		endif;

		return $valor;
	}

	function compararValores( $valor1, $valor2, $campo1, $campo2, $accion )
	{

		if( $accion == 1 ){
			if( $valor1 == $valor2 ){
				$this->error   = TRUE;
				$this->mensaje = "El valor de " . $campo1 . " no debe ser igual " . $campo2;
			}
		}
		elseif( $accion == 2 ){
			if( $valor1 > $valor2 ){
				$this->error   = TRUE;
				$this->mensaje = "El valor de " . $campo2 . " no puede ser menor " . $campo1;
			}
		}
		elseif( $accion == 3 ){
			if( $valor1 < $valor2 ){
				$this->error   = TRUE;
				$this->mensaje = "El valor de " . $campo1 . " no debe ser igual " . $campo2;
			}
		}
	}


	function validarCantidades( $cantidad1, $cantidad2 )
	{
		if( $this->error )
			return;

		if( $cantidad1 < $cantidad2 ){
			$this->error   = TRUE;
			$this->mensaje = "Existe un faltante de: <b>Q. " . diferenciaCantidad( $cantidad1, $cantidad2  ) . "</b>";
		}
	}


	// VALIDAR CANTIDAD MONETARIA
	function validarDinero( $valor = 0, $default = NULL, $required = TRUE,  $mensaje = '' )
	{
		if( $this->error )
			return $valor;
		
		$warning = FALSE;
		$valor   = (double)$valor;
		$msj = "";

		if ( !is_numeric( $valor ) ):
			$warning = TRUE;
			$msj     = "El valor ingresado en {$mensaje} no es un número válido";

		elseif( $valor <= 0 ):
			$warning = TRUE;
			$msj     = "El valor ingresado en {$mensaje} debe ser mayor a 0";

		endif;

		if ( !$required AND $warning ):
			$valor = $default;

		elseif( $warning AND $required ):
			$this->error   = TRUE;
			$this->mensaje = $msj;

		endif;

		return $valor;
	}

	
	// VALIDAR CORREO
	function validarCorreo( $valor = '', $default = NULL, $required = TRUE )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;

		if ( !filter_var( $valor, FILTER_VALIDATE_EMAIL ) )
			$warning = TRUE;

		if( $warning AND !$required )
			$valor = $default;

		if ( $required AND $warning ) {
			$this->tiempo  = 4;
			$this->mensaje = 'Correo electronico invalido, verifique.';
			$this->error   = TRUE;
		}

		return $valor;
	}

	
	// VALIDAR ENTERO
	function validarCantidad( $valor = 0, $default = NULL, $required = TRUE, $minimo = 0, $maximo = 999999,  $msj = '' )
	{
		if( $this->error )
			return $valor;
		
		$warning = FALSE;
		$valor   = (double)$valor;
		$msj = "";

		if ( !is_numeric( $valor ) ):
			$warning = TRUE;
			$msj     = "El valor ingresado en {$msj} no es númerico, verifique.";

		elseif( $valor < $minimo ):
			$warning = TRUE;
			$msj     = "El valor ingresado en {$msj} no puede ser menor a {$minimo}, verifique.";

		elseif( $valor > $maximo ):
			$warning = TRUE;
			$msj     = "El valor ingresado en {$msj} no puede ser mayor a {$maximo}, verifique.";

		endif;


		if ( !$required AND $warning ):
			$valor = $default;

		elseif( $warning AND $required ):
			$this->error   = TRUE;
			$this->mensaje = $msj;

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

		if ( !filter_var( $valor, FILTER_VALIDATE_INT ) )
			$warning = TRUE;

		if ( !$required AND $warning )
			$valor = $default;

		if( $warning AND $required ):
			$this->mensaje = $msj;
			$this->error   = TRUE;
		endif;

		return $valor;
	}

	
	// VALIDAR TEXTO
	function validarTexto( $valor = 0, $default = NULL, $required = TRUE, $minimo = 1, $maximo = 350, $mensaje = '' )
	{
		if( $this->error )
			return $valor;

		$warning = FALSE;
		$valor   = (string)$valor;
		$msj     = "";

		if( !strlen( $valor ) > 0 AND $required ):
			$warning = TRUE;
			$msj     = "No ha ingresado {$mensaje}, verifique.";

		elseif( strlen( $valor ) < $minimo ):
			$warning = TRUE;
			$msj     = "El contenido ingresado en {$mensaje} no puede ser menor a {$minimo} caracteres, verifique.";

		elseif( strlen( $valor ) > $maximo ):
			$warning = TRUE;
			$msj     = "El contenido ingresado en {$mensaje} no puede ser mayor a {$maximo} caracteres, verifique.";
			
		endif;

		if( $warning AND $required ):
			$this->error   = TRUE;
			$this->mensaje = $msj;

		elseif( $warning AND !$required ):
			$valor = $default;

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