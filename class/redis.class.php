<?php
/**
 * CLASE PARA ENVIAR DATOS DE REDIS A NODEJS
 */

class Redis
{
 	function messageRedis( $dataTxt )
 	{
 		// LIBRERIA NECESARIA
 		require __DIR__.'/../libs/predis-1.1/autoload.php';

 		// NOMBRE DEL CANAL
 		$channel = "ch_restaurante";

 		// DATOS DE CONEXION
		$single_server = array(
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 15,
		);
		
		// NUEVA INSTANCIA DE CLIENTE REDIS
		$client = new Predis\Client( $single_server );

		// CONVERSION A JSON
		$dataTxt = json_encode( $dataTxt );

		// PUBLICAR Y RETORNAR 
		return $client->publish( $channel, $dataTxt );
 	}
}

$o = new Redis();
$o->messageRedis( array( "hora" => date("Y-m-d H:i:s") ) );
?>