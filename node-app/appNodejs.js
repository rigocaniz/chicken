var express = require('express');  
var app     = express();  
var server  = require('http').Server(app);  
var io      = require('socket.io')(server); 
var redis = require("redis");
var client = redis.createClient( 6379, '127.0.0.1' );

// CONEXION CON REDIS

client.subscribe("ch_restaurante");

io.on('connection', function (socket) {
	console.log( "Un cliente conectado" );
	socket.emit('mensaje', "mi mensaje");

	client.on("message", function (channel, message) {
	    console.log("sub channel " + channel + ": " + message);

	    // VERIFICAR SI EXISTE UN MENSAJE VALIDO
	    if ( message && message.length > 2 ) {
	    	var data = JSON.parse( message );
	    	socket.emit('info', { 'accion' : 'ordenNueva', 'data' : data });
	    }
	});
});

server.listen(8080, function () {
	console.log("SERVER UP");
});
