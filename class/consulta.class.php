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


	// CATALOGO FORMAS PAGO
 	function catFormasPago()
 	{
 		$catFormasPago = [];

 		$sql = "SELECT 
				    idFormaPago, 
				    formaPago, 
				    porcentajeRecargo, 
				    montoRecargo
					FROM
						formaPago
					ORDER BY idFormaPago;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catFormasPago[] = $row;
 		}

 		return $catFormasPago;
 	}


 	// CATALOGO ESTADOS FACTURA
 	function catEstadosFactura()
 	{
 		$catEstadosFactura = array();
 		$sql = "SELECT idEstadoFactura, estadoFactura FROM estadoFactura;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while ( $row = $rs->fetch_assoc() )
 				$catEstadosFactura[] = $row;
 		}

 		return $catEstadosFactura;
 	}

	
	// CATALOGO UBICACIÓN PRODUCTO
 	function catUbicacion()
 	{
 		$catUbicacion = array();
 		$sql = "SELECT * FROM ubicacion;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while ( $row = $rs->fetch_assoc() )
 				$catUbicacion[] = $row;
 		}

 		return $catUbicacion;
 	}


 	function catTiposServicio()
 	{
 		$catTiposServicio = array();
 		$sql = "SELECT idTipoServicio, tipoServicio, orden FROM tipoServicio ORDER BY orden;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catTiposServicio[] = $row;
 		}
 		
 		return $catTiposServicio;
 	}


 	function catEstadoMenu()
 	{
 		$catEstadoMenu = array();
 		$sql = "SELECT idEstadoMenu, estadoMenu FROM estadoMenu;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catEstadoMenu[] = $row;
 		}
 		
 		return $catEstadoMenu;
 	}


	function catMedidas()
 	{
 		$catMedidas = array();
 		$sql = "SELECT idMedida, medida FROM medida ORDER BY idMedida;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catMedidas[] = $row;
 		}
 		
 		return $catMedidas;
 	}


	function catDestinoMenu()
 	{
 		$catDestinoMenu = array();
 		$sql = "SELECT idDestinoMenu, destinoMenu FROM destinoMenu ORDER BY idDestinoMenu;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catDestinoMenu[] = $row;
 		}
 		
 		return $catDestinoMenu;
 	}


 	function catTipoProducto()
 	{
 		$catTipoProducto = array();
 		$sql = "SELECT idTipoProducto, tipoProducto FROM tipoProducto ORDER BY idTipoProducto;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catTipoProducto[] = $row;
 		}
 		
 		return $catTipoProducto;
 	}


	function catEstadoOrden()
 	{
 		$catEstadoOrden = array();
 		$sql = "SELECT idEstadoOrden, estadoOrden FROM estadoOrden ORDER BY idEstadoOrden;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catEstadoOrden[] = $row;
 		}
 		
 		return $catEstadoOrden;
 	}

	
 	function catTiposCliente()
 	{
 		$catTiposCliente = array();

 		$sql = "SELECT idTipoCliente, tipoCliente FROM tipoCliente ORDER BY idTipoCliente;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catTiposCliente[] = $row;
 		}

 		return $catTiposCliente;
 	}


 	function catEstadoCaja()
 	{
 		$catEstadoCaja = array();

 		$sql = "SELECT idEstadoCaja, estadoCaja FROM estadoCaja ORDER BY idEstadoCaja;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$catEstadoCaja[] = $row;
 		}

 		return $catEstadoCaja;
 	}

 	function catTipoMenu()
 	{
 		$lst = array();

 		$sql = "SELECT idTipoMenu, tipoMenu FROM tipoMenu ";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$lst[] = $row;
 		}

 		return $lst;
 	}

 	function catSalon()
 	{
 		$lst = array();

 		$sql = "SELECT idSalon, salon FROM salon ";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() )
 				$lst[] = $row;
 		}

 		return $lst;
 	}

 	function catTipoMovimiento()
 	{
 		$lst = array();

 		$sql = "SELECT idTipoMovimiento, tipoMovimiento, ingreso FROM tipoMovimiento ";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_assoc() ){
				$row[ 'ingreso' ] = (int)$row[ 'ingreso' ];
				$lst[]        = $row;
 			}
 		}

 		return $lst;
 	}
}

?>