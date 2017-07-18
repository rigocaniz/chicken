<?php 

	function validarNulo( $valor )
	{
		$esNulo = is_null( $valor ) ? TRUE : FALSE;

		return $esNulo;
	}


	// Validar Valor Nulo
	function esNulo( $valor = '' )
	{
		$esNulo = FALSE;
		if( is_null( $valor ) )
			$esNulo = TRUE;

		return $esNulo;
	}

?>