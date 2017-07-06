<?php 
/**
* CONEXION
*/
class Conexion extends mysqli {

	private $server = "localhost";
	private $user   = "root";
	private $db     = "dbChurchill";
	private $pass   = "";
	private $con    = false;


	// INICIALIZAR CONSTRUCTOR DE LA BD
	function __construct()
	{
		if( !$this->con )
		{
			parent::__construct($this->server, $this->user, $this->pass, $this->db);
        	parent::set_charset( 'utf8' );

        	if( $this->connect_error )
            	die( $this->connect_errno );
		}

	}

}

$conexion = new Conexion();

?>