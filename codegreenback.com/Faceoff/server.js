var socket = require('socket.io'),
	express = require('express'),
	https = require('https'),
	fs = require('fs'),
	http = require('http');
	var app = express();
	//var http_server = http.createServer(app).listen(1528);
	var https_serv = https.createServer({
		key: fs.readFileSync('/etc/letsencrypt/live/www.codegreenback.com/privkey.pem'),
		cert: fs.readFileSync('/etc/letsencrypt/live/www.codegreenback.com/cert.pem'),
		ca: fs.readFileSync('/etc/letsencrypt/live/www.codegreenback.com/chain.pem'),
		requestCert: false,
		rejectUnauthorized: false 
		},app);
	users = {};

	https_serv.listen(1528)

var io = socket.listen( https_serv );

io.sockets.on('connection',function (socket,data)
{
	var room_id;

	socket.on("hi",function(data)
	{
		socket.join(data.challengeid);
		room_id = data.challengeid;
		var rooms = Object.keys(io.sockets.adapter.sids[socket.id]);
		socket.broadcast.to(data.challengeid).emit('online',{description: "user is online", room: rooms[1]});
	});


	socket.on("dataInitial",function(data,callback){

		io.to(data.challengeid).emit("new_order",data.quesNo);

	})

	socket.on("disconnect", function () {
		// console.log("A user disconnected");
		
		socket.broadcast.to(room_id).emit('ofline',{description: "user is ofline"});
	});
	
	socket.on("001", function(){
		socket.broadcast.to(room_id).emit('001');
	});

	socket.on("110", function () {
    socket.broadcast.to(room_id).emit("110");
	});
	
	socket.on("010", function () {
        socket.broadcast.to(room_id).emit("010");
	});
	
	socket.on("100", function () {
        socket.broadcast.to(room_id).emit("100");
	});
	
	socket.on("101", function () {
        socket.broadcast.to(room_id).emit("101");
    });

	  socket.on("111", function(data, callback){                                   //sent only on winning
		io.to(data.room_id).emit('111', data.winner)
	});
});
