var app = require('express')(),
        server = require('http').createServer(app),
        io = require('socket.io').listen(server),
        ent = require('ent'), // Permet de bloquer les caractères HTML (sécurité équivalente à htmlentities en PHP)
        fs = require('fs'),
        mysql = require('mysql'),
        connection = mysql.createConnection({
            host: 'localhost',
            user: 'root',
            password: '',
            database: 'my_events'
        });

// usernames which are currently connected to the chat
var usernames = [];
var historique = [];

io.sockets.on('connection', function(socket) {

    socket.on('getHistorique', function(destinataireId, utilisateurId) {

        connection.query('SELECT historique_conversation FROM amis where utilisateur_emetteur = ? AND utilisateur_destinataire = ?', [utilisateurId, destinataireId], function(err, rows) {
            if (err)
                throw err;
            if (!rows.length) {
                connection.query('SELECT historique_conversation FROM amis where utilisateur_emetteur = ? AND utilisateur_destinataire = ?', [destinataireId, utilisateurId], function(errBis, rowsBis) {
                    if (rowsBis[0].historique_conversation && rowsBis[0].historique_conversation.length) {
                        historique = JSON.parse(rowsBis[0].historique_conversation);
                        socket.emit('historique', historique);
                    }
                });
            } else {

                if (rows[0].historique_conversation && rows[0].historique_conversation.length) {
                    console.log('test');
                    historique = JSON.parse(rows[0].historique_conversation);
                    socket.emit('historique', historique);
                }
            }
        });

    });

    socket.on('getSocket', function(users) {
//        if (users.length) {
//            usernames = users;
//            console.log(usernames);
//        }
    });

    // when the client emits 'adduser', this listens and executes
    socket.on('adduser', function(username) {
        // we store the username in the socket session for this client if user not exits
        if (typeof usernames[username] === "undefined") {
            socket.username = username;
            // add the client's username to the global list
            usernames[username] = socket.id;
            //socket.emit('saveUsers', JSON.parse(socket));
        }
    });

    // when the user disconnects
    socket.on('disconnect', function() {
        // remove the username from global usernames list
        usernames.splice(socket.username, 1);
    });

    socket.on('leave_conversation', function(usr) {
        // Tell the user has left the conversation
        io.sockets.socket(usernames[usr]).emit('msg_user_leave', socket.username + " a quitté(e) la conversation");
    });

    // when the user sends a private message to a user
    socket.on('msg_user', function(usr, username, msg, room, utilisateurId, destinataireId) {
        if (typeof usernames[usr] === "undefined") {
            io.sockets.emit('chatConnect', usr);
        }
        // Sauvegarde de l'historique
        historique.push({username: username, message: msg});
        connection.query('UPDATE amis set historique_conversation = ? where utilisateur_emetteur = ? AND utilisateur_destinataire = ?', [JSON.stringify(historique), utilisateurId, destinataireId], function(err, rows) {
            if (err)
                throw err;
        });
        io.sockets.socket(usernames[usr]).emit('msg_user_handle', usr, username, msg, destinataireId);
    });

});

server.listen(8080);