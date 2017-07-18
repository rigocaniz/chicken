<?php 
/**
* USUARIO
*/
class Usuario
{
	
	function __construct(argument)
	{
		
	}

	function login( $usuario, $clave )
	{


		$sql = "CALL login( '{$usuario}', '{$clave}' )";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$this->respuesta = $row->respuesta;
				$this->mensaje   = $row->mensaje;
				$this->data      = $row;
			}
		}
		else{
			$this->respuesta = 'danger';
			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
		}
		
	}
}
?>