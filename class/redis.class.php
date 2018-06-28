<?php
/**
 * CLASE PARA ENVIAR DATOS DE REDIS A NODEJS
 */

class Redis
{
	var $client  = null;
	var $channel = "ch_restaurante"; // NOMBRE DEL CANAL
	function __construct()
	{
 		// LIBRERIA NECESARIA
 		require __DIR__.'/../libs/predis-1.1/autoload.php';


 		// DATOS DE CONEXION
		$single_server = array(
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 15,
		);
		
		// NUEVA INSTANCIA DE CLIENTE REDIS
		$this->client = new Predis\Client( $single_server );
	}

 	function messageRedis( $dataTxt )
 	{

		// CONVERSION A JSON
		$dataTxt = json_encode( $dataTxt );

		// PUBLICAR Y RETORNAR 
		return $this->client->publish( $this->channel, $dataTxt );
 	}
}

?>