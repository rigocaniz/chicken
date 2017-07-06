<?php
/**
* COMOBO
*/
class Combo
{

	private $respuesta = 'info';
 	private $mensaje   = '';
 	private $tiempo    = 6;
 	private $error     = FALSE;
 	private $sess      = NULL;
 	private $con       = NULL;
 	private $data      = array();

 	function __construct()
 	{
 		GLOBAL $conexion, $sesion;
 		$this->con  = $conexion;
 		$this->sess = $sesion;
 	}


 	// CONSULTAR DATOS COMBO
	function cargarCombo( $idCombo )
	{
		$idCombo = (int)$idCombo;
		$combo = array();

		$sql = "SELECT 
				    idCombo,
				    combo,
				    imagen,
				    descripcion,
				    idEstadoMenu
				FROM
				    lstCombo
				WHERE
				    idCombo = {$idCombo};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$combo = $row;
		}

		return $combo;
	}


	// CONSULTAR DATOS COMBO DETALLE
	function cargarComboDetalle( $idCombo, $idMenu )
	{
		$idCombo = (int)$idCombo;
		$idMenu  = (int)$idMenu;
		$comboDetalle = array();

		$sql = "SELECT 
				    idCombo,
				    idMenu,
				    cantidad
				FROM
				    lstComboDetalle
				WHERE
				    idCombo = {$idCombo} AND idMenu = {$idMenu};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$comboDetalle = $row;
		}

		return $comboDetalle;
	}

	// CONSULTAR DATOS COMBO PRECIO
	function cargarComboPrecio( $idCombo, $idTipoServicio )
	{
		$idCombo        = (int)$idCombo;
		$idTipoServicio = (int)$idTipoServicio;
		$comboPrecio = array();

		$sql = "SELECT 
				    idCombo,
				    precio,
				    idTipoServicio
				FROM
				    lstComboPrecio
				WHERE
				   idCombo = {$idCombo} AND idTipoServicio = {$idTipoServicio};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$comboPrecio = $row;
		}

		return $comboPrecio;
	}


	// CONSULTAR DATOS SUPER COMBO
	function cargarSuperCombo( $idSuperCombo )
	{
		$idSuperCombo = (int)$idSuperCombo;
		$superCombo = array();

		$sql = "SELECT 
				    idSuperCombo,
				    superCombo,
				    imagen,
				    descripcion,
				    idEstadoMenu
				FROM
				    lstSuperCombo
				WHERE
				   idSuperCombo = {$idSuperCombo};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$superCombo = $row;
		}

		return $superCombo;
	}


	// CONSULTAR DATOS SUPER COMBO DETALLE
	function cargarSuperComboDetalle( $idCombo, $idSuperCombo )
	{
		$idCombo      = (int)$idCombo;
		$idSuperCombo = (int)$idSuperCombo;
		$superComboDetalle = array();

		$sql = "SELECT 
				    idCombo,
				    idSuperCombo,
				    cantidad
				FROM
				    lstSuperComboDetalle
				WHERE
				   idCombo = {$idCombo} AND idSuperCombo = {$idSuperCombo};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$superComboDetalle = $row;
		}

		return $superComboDetalle;
	}


	// CONSULTAR DATOS SUPER COMBO PRECIO
	function cargarSuperComboPrecio( $idSuperCombo, $idTipoServicio )
	{
		$idSuperCombo   = (int)$idSuperCombo;
		$idTipoServicio = (int)$idTipoServicio;
		$superComboPrecio = array();

		$sql = "SELECT 
				    idSuperCombo,
				    precio,
				    idTipoServicio
				FROM
				    lstSuperComboPrecio
				WHERE
				   idSuperCombo = {$idSuperCombo} AND idTipoServicio = {$idTipoServicio};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$superComboPrecio = $row;
		}

		return $superComboPrecio;
	}

	


 	// CONSULTAR LISTA DE COMBOS
 	function lstCombo()
 	{
 		$lstCombo = array();

 		$sql = "SELECT idCombo, combo, imagen, descripcion, idEstadoMenu, estadoMenu FROM lstCombo;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstCombo[] = $row;
 			}
 		}

 		return $lstCombo;
 	}


 	// CONSULTAR LISTA DE COMBOS DETALLE
 	function lstComboDetalle()
 	{
 		$lstComboDetalle = array();

 		$sql = "SELECT idCombo, cantidad, idMenu, menu, imagen, descripcion, idEstadoMenu, estadoMenu FROM lstComboDetalle;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstComboDetalle[] = $row;
 			}
 		}

 		return $lstComboDetalle;
 	}


 	// CONSULTAR LISTA DE COMBOS PRECIO
 	function lstComboPrecio()
 	{
 		$lstComboPrecio = array();

 		$sql = "SELECT idCombo, precio, idTipoServicio, tipoServicio FROM lstComboPrecio;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstComboPrecio[] = $row;
 			}
 		}

 		return $lstComboPrecio;
 	}

 	// CONSULTAR LISTA DE COMBOS
 	function lstSuperCombo()
 	{
 		$lstSuperCombo = array();

 		$sql = "SELECT idSuperCombo, superCombo, imagen, descripcion, idEstadoMenu, estadoMenu FROM lstSuperCombo;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstSuperCombo[] = $row;
 			}
 		}

 		return $lstSuperCombo;
 	}


 	// CONSULTAR LISTA DE COMBOS PRECIO
 	function lstSuperComboDetalle()
 	{
 		$lstSuperComboDetalle = array();

 		$sql = "SELECT idSuperCombo, cantidad, idCombo, combo, imagen, descripcion, idEstadoMenu, estadoMenu FROM lstSuperComboDetalle;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstSuperComboDetalle[] = $row;
 			}
 		}

 		return $lstSuperComboDetalle;
 	}


 	// CONSULTAR LISTA DE COMBOS PRECIO
 	function lstSuperComboPrecio()
 	{
 		$lstSuperComboPrecio = array();

 		$sql = "SELECT idSuperCombo, precio, idTipoServicio, tipoServicio FROM lstSuperComboPrecio;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstSuperComboPrecio[] = $row;
 			}
 		}

 		return $lstSuperComboPrecio;
 	}

	
	// GUARDAR // ACTUALIZAR => CONSULTA COMBO
	function consultaCombo( $accion, $data )
 	{
 		$validar = new Validar();

		// INICIALIZACIÓN VAR
		$idCombo      = 'NULL';
		$combo        = NULL;
		$imagen       = "NULL";
		$descripcion  = NULL;
		$idEstadoMenu = NULL;

		// SETEO VARIABLES GENERALES
 		$data->combo        = strlen( $data->combo ) > 0 ? (string)$data->combo : NULL;
 		$data->descripcion  = strlen( $data->descripcion ) > 0 ? (string)$data->descripcion : NULL;
 		$data->idEstadoMenu = (int)$data->idEstadoMenu > 0 AND !esNulo( $data->idEstadoMenu ) ? (int)$data->idEstadoMenu : NULL;
 
 		// VALIDACIONES
 		if( $accion == 'update' ):
 			$data->idCombo = (int)$data->idCombo > 0 ? (int)$data->idCombo : NULL;
 			$idCombo       = $validar->validarEntero( $data->idCombo, NULL, TRUE, 'El ID del COMBO no es válido, verifique.' );
 		endif;

		$combo        = $validar->validarTexto( $data->combo, NULL, TRUE, 3, 45, 'el nombre del combo' );
		$descripcion  = $validar->validarTexto( $data->descripcion, NULL, TRUE, 15, 45, 'la descripcion' );
		$idEstadoMenu = $validar->validarEntero( $data->idEstadoMenu, NULL, TRUE, 'El ID del estado combo no es válido, verifique.' );


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaCombo( '{$accion}', {$idCombo}, '{$combo}', {$imagen}, '{$descripcion}', {$idEstadoMenu} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $this->respuesta == 'success' )
	 					$this->data = (int)$row->id;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
 	}


 	// GUARDAR // ACTUALIZAR => COMBO PRECIO
	function consultaComboPrecio( $accion, $data )
 	{
 		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idCombo        = NULL;
 		$idTipoServicio = NULL;

		// SETEO VARIABLES GENERALES
		$data->idTipoServicio = (int)$data->idTipoServicio > 0 	? (int)$data->idTipoServicio 	: NULL;
		$data->precio         = (double)$data->precio 	 		? (double)$data->precio 		: NULL;

		$idCombo        = $validar->validarEntero( $data->idCombo, NULL, TRUE, 'El ID del menú no es válido, verifique.' );
		$idTipoServicio = $validar->validarEntero( $data->idTipoServicio, NULL, TRUE, 'El ID del tipo de servicio no es válido, verifique.' );
		
		$precio = "NULL";
		if( $accion <> 'delete' ):
			$data->idCombo = (int)$data->idCombo > 0 ? (int)$data->idCombo : NULL;
			$precio = $validar->validarCantidad( $data->precio, NULL, TRUE, 1, 2500, 'el precio del menú' );
		endif;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaComboPrecio( '{$accion}', {$idCombo}, {$idTipoServicio}, {$precio} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
 	}


 	// GUARDAR // ACTUALIZAR // ELIMINAR => COMBO DETALLE
	function consultaComboDetalle( $accion, $data )
	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idCombo  = NULL;
 		$idMenu   = NULL;

		// SETEO VARIABLES GENERALES
		$data->idCombo  = (int)$data->idCombo > 0 	? (int)$data->idCombo 		: NULL;
		$data->idMenu   = (int)$data->idMenu > 0 	? (int)$data->idMenu 		: NULL;

		$idCombo  = $validar->validarEntero( $data->idCombo, NULL, TRUE, 'El ID del Combo no es válido, verifique.' );
		$idMenu   = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El ID del Menu de servicio no es válido, verifique.' );
		
 		$cantidad = "NULL";
		// ELIMINAR
		if( $accion <> 'delete' ):
			$data->cantidad = (double)$data->cantidad ? (double)$data->cantidad : NULL;
			$cantidad       = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 2500, 'la cantidad' );
		endif;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaComboDetalle( '{$accion}', {$idCombo}, {$idMenu}, {$cantidad} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
	}


	// GUARDAR // ACTUALIZAR => CONSULTA SUPER COMBO
	function consultaSuperCombo( $accion, $data )
 	{
 		$validar = new Validar();

		// INICIALIZACIÓN VAR
		$idSuperCombo = 'NULL';
		$superCombo   = NULL;
		$imagen       = "NULL";
		$descripcion  = NULL;
		$idEstadoMenu = NULL;

		// SETEO VARIABLES GENERALES
 		$data->superCombo   = strlen( $data->superCombo ) > 0 ? (string)$data->superCombo : NULL;
 		$data->descripcion  = strlen( $data->descripcion ) > 0 ? (string)$data->descripcion : NULL;
 		$data->idEstadoMenu = (int)$data->idEstadoMenu > 0 AND !esNulo( $data->idEstadoMenu ) ? (int)$data->idEstadoMenu : NULL;
 
 		// VALIDACIONES
 		if( $accion == 'update' ):
 			$data->idSuperCombo = (int)$data->idSuperCombo > 0 ? (int)$data->idSuperCombo : NULL;
 			$idSuperCombo       = $validar->validarEntero( $data->idSuperCombo, NULL, TRUE, 'El ID del Super Combo no es válido, verifique.' );
 		endif;

		$superCombo   = $validar->validarTexto( $data->superCombo, NULL, TRUE, 3, 45, 'el nombre del Super Combo' );
		$descripcion  = $validar->validarTexto( $data->descripcion, NULL, TRUE, 15, 45, 'la descripcion' );
		$idEstadoMenu = $validar->validarEntero( $data->idEstadoMenu, NULL, TRUE, 'El ID del estado combo no es válido, verifique.' );


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaSuperCombo( '{$accion}', {$idSuperCombo}, '{$superCombo}', {$imagen}, '{$descripcion}', {$idEstadoMenu} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $this->respuesta == 'success' )
	 					$this->data = (int)$row->id;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
 	}


 	// GUARDAR // ACTUALIZAR // ELIMINAR => COMBO DETALLE
	function consultaSuperComboDetalle( $accion, $data )
	{
		 $validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idSuperCombo = NULL;
 		$idCombo      = NULL;

		// SETEO VARIABLES GENERALES
		$data->idSuperCombo = (int)$data->idSuperCombo > 0 	? (int)$data->idSuperCombo 	: NULL;
		$data->idCombo      = (int)$data->idCombo > 0 		? (int)$data->idCombo 		: NULL;

		$idSuperCombo = $validar->validarEntero( $data->idSuperCombo, NULL, TRUE, 'El ID del Super Combo no es válido, verifique.' );
		$idCombo      = $validar->validarEntero( $data->idCombo, NULL, TRUE, 'El ID del Combo no es válido, verifique.' );

 		$cantidad = "NULL";
		// ELIMINAR
		if( $accion <> 'delete' ):
			$data->cantidad = (double)$data->cantidad ? (double)$data->cantidad : NULL;
			$cantidad       = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 2500, 'la cantidad' );
		endif;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaSuperComboDetalle( '{$accion}', {$idCombo}, {$idSuperCombo}, {$cantidad} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
	}


 	// OBTENER ARREGLO RESPUESTA
 	private function getRespuesta()
 	{

 		if( $this->respuesta == 'success' )
 			$this->tiempo = 4;
 		elseif( $this->respuesta == 'warning')
 			$this->tiempo = 5;
 		elseif( $this->respuesta == 'danger')
 			$this->tiempo = 7;

 		return $respuesta = array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo,
 				'data'      => $this->data
 			);
 	}

}
?>