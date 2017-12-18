<?php 
	// Validar Valor Nulo
	function esNulo( $valor = '' )
	{
		$esNulo = FALSE;
		if( is_null( $valor ) )
			$esNulo = TRUE;

		return $esNulo;
	}


	function diferenciaCantidad( $cantidad1, $cantidad2 )
	{
		$total = $cantidad2 - $cantidad1;
		return number_format( $total, 2 );
	}

?>