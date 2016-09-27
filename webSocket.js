var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var parser = require('body-parser');

app.use(parser.urlencoded({ extended: true }));

var clients = [];


app.all("/pushNotification", function (req, res) {
    console.log(req.body);

    for (var i in clients) {
        var iClient = clients[i];
        iClient.emit("reload",
            {   canReload: true,
                id: req.body.id,
                lat: req.body.lat,
                lng: req.body.lng   }
        );
    }

    res.setHeader('Content-Type', 'application/json');
    res.send(JSON.stringify({ code: 200 }));
});

io.on('connection', function(socket){
    clients.push(socket);

});

http.listen(3000, function(){
    console.log('listening on *:3000');
});