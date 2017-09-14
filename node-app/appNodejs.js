var express = require('express');  
var app     = express();  
var server  = require('http').Server(app);  
var io      = require('socket.io')(server); 
var redis = require("redis");
var client = redis.createClient( 6379, '127.0.0.1' );

// CONEXION CON REDIS

client.subscribe("ch_restaurante");

var lstUsers = [];

io.on('connection', function (socket) {
	console.log( "Un cliente conectado" );

	socket.on('sesion', function ( data ) {
		var _user = data.code.split( "." );

		if ( _user.length == 3 )
		{
			lstUsers.push({
				user     : _user[ 1 ],
				idPerfil : _user[ 2 ],
				id       : socket.id
			});
			console.log( lstUsers );
		}
	});

	client.on("message", function (channel, message) {

	    // VERIFICAR SI EXISTE UN MENSAJE VALIDO
	    if ( message && message.length > 2 ) {
	    	var data = JSON.parse( message );
	    	socket.emit('info', data );
	    }
	});

	socket.on('disconnect', function () {
		// ELIMINA USUARIO
		var index = -1;
		for (var ix = 0; ix < lstUsers.length; ix++)
		{
			if ( lstUsers[ ix ].id == socket.id )
			{
				index = ix;
				break;
			}
		}

		if ( index >= 0 )
			lstUsers.splice( index, 1 );

		console.log( lstUsers );
	});
});

server.listen(8080, function () {
	console.log("SERVER UP");
});
