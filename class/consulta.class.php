<?php
/**
 * CONSULTAS BÁSICAS
 */
class Consulta
{

 	private $sess      = NULL;
 	private $con       = NULL;

 	function __construct()
 	{
 		GLOBAL $conexion, $sesion;
 		$this->con  = $conexion;
 		$this->sess = $sesion;
 	}

 	
 	// LST CATALOGO TIPOS DE SERVICIO
 	function catTiposServicio()
 	{
 		$catTiposServicio = array();
 		$sql = "SELECT idTipoServicio, tipoServicio, orden FROM tipoServicio ORDER BY orden;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$catTiposServicio[] = $row;
 		}
 		
 		return $catTiposServicio;
 	}


	// LST CATALOGO ESTADO MENU
 	function catEstadoMenu()
 	{
 		$catEstadoMenu = array();
 		$sql = "SELECT idEstadoMenu, estadoMenu FROM estadoMenu;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$catEstadoMenu[] = $row;
 		}
 		
 		return $catEstadoMenu;
 	}


 	// LST CATALOGO MEDIDAS
	function catMedidas()
 	{
 		$catMedidas = array();
 		$sql = "SELECT idMedida, medida FROM medida ORDER BY idMedida;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$catMedidas[] = $row;
 		}
 		
 		return $catMedidas;
 	}


 	// LST CATALOGO MEDIDAS
	function catDestinoMenu()
 	{
 		$catDestinoMenu = array();
 		$sql = "SELECT idDestinoMenu, destinoMenu FROM destinoMenu ORDER BY idDestinoMenu;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$catDestinoMenu[] = $row;
 		}
 		
 		return $catDestinoMenu;
 	}


	// LST CATALOGO TIPOS PRODUCTO
 	function catTipoProducto()
 	{
 		$catTipoProducto = array();
 		$sql = "SELECT idTipoProducto, tipoProducto FROM tipoProducto ORDER BY idTipoProducto;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$catTipoProducto[] = $row;
 		}
 		
 		return $catTipoProducto;
 	}


 	// LST CATALOGO ESTADOS ORDEN
	function catEstadoOrden()
 	{
 		$catEstadoOrden = array();
 		$sql = "SELECT idEstadoOrden, estadoOrden FROM estadoOrden ORDER BY idEstadoOrden;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$catEstadoOrden[] = $row;
 		}
 		
 		return $catEstadoOrden;
 	}

	
	// LST CATALOGO TIPOS CLIENTE
 	function catTiposCliente()
 	{
 		$catTiposCliente = array();

 		$sql = "SELECT idTipoCliente, tipoCliente FROM tipoCliente ORDER BY idTipoCliente;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$catTiposCliente[] = $row;
 		}

 		return $catTiposCliente;
 	}


 	// LST CATALOGO TIPOS CLIENTE
 	function catEstadoCaja()
 	{
 		$catEstadoCaja = array();

 		$sql = "SELECT idEstadoCaja, estadoCaja FROM estadoCaja ORDER BY idEstadoCaja;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$catEstadoCaja[] = $row;
 		}

 		return $catEstadoCaja;
 	}

}

?>