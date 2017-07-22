var express = require('express');  
var app     = express();  
var server  = require('http').Server(app);  
var io      = require('socket.io')(server); 


app.use( express.static('./') );

app.get('/hello', function (req, res) {
	res.status(200).send("Hello World.!");
});

io.on('connection', function (socket) {
	console.log( "Un cliente conectado" );
	socket.emit('mensaje', "mi mensaje");
});

server.listen(8080, function () {
	console.log("SERVER UP");
});
