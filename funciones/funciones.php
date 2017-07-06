<?php 

	// Validar Valor Nulo
	function esNulo( $valor = '' )
	{
		$esNulo = FALSE;
		if( is_null( $valor ) )
			$esNulo = TRUE;

		return $esNulo;
	}

?>