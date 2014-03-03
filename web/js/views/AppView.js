define([
    'jquery',
    'underscore',
    'backbone'
], function($, _, Backbone) {
    var ListAmisView = Backbone.View.extend({
        el: 'body',
        events: {
            'click .cross': 'closeChat'
        },
        initialize: function(options) {
            var self = this;
            this.options = options;
            this.room = this.options.room;
//            this.collection = options.collection;
//            this.collection.bind('change', self.render, this);
//            this.collection.bind('destroy', self.remove, this);
        },
        render: function() {
            var self = this;

            if (!$('#chat').hasClass('open')) {
                self.$el.fadeIn(250, function() {
                    self.openChat();
                    $('#chat').addClass('open');
                });
            }

            return this;
        },
        openChat: function() {
            var $el = this.$el,
                    options = this.options,
                    self = this;

            require(['socket-io'], function(io) {
                var templateChat = self.constructor.templateChat,
                        pseudo = $('#amis-list').data('utilisateur'),
                        utilisateurId = $('#amis-list').data('id'),
                        destinataire = "",
                        destinataireId = -1;

                if (typeof options.destinataire !== "undefined") {
                    destinataire = options.destinataire;
                }
                if (typeof options.destinataireId !== "undefined") {
                    destinataireId = options.destinataireId;
                }

                // Connexion à socket.io
                var socket = io.connect('http://my-events.local:8080');

                // Envoi d'un évènement pour récuperer l'historique de la conversation
                socket.emit('getHistorique', utilisateurId, destinataireId);

                $('#chat').html(templateChat({destinataire: destinataire}));
                $('#message').focus();

                // Récupération de l'historique
                socket.on('historique', function(histo) {

                    _.each(histo, function(message) {
                        self.insereMessage(message.username, message.message);
                    });
                    $('#zone_chat').scrollTop($('#zone_chat').offset().top);
                });

                // Récupération des utilisateurs lors du rechargement de la page
                socket.on('connect', function() {
                    var users = localStorage.getItem('socket');
                    socket.emit('getSocket', users);
                });

                // A la création d'un nouvel utilisateur, on le sauvegarde dans le localStorage
                socket.on('saveUsers', function(users) {
                    users = JSON.stringify(users);
                    localStorage.setItem('socket', users);
                });

                // Send event to server to add user
                socket.emit('adduser', pseudo);

                // Lorsqu'on envoie le formulaire, on transmet le message et on l'affiche sur la page
                $('#formulaire_chat').submit(function() {
                    var message = $('#message').val();
                    socket.emit('msg_user', destinataire, pseudo, message, self.room, destinataireId, utilisateurId); // Transmet le message aux autres
                    self.insereMessage(pseudo, message); // Affiche le message aussi sur notre page
                    $('#message').val('').focus(); // Vide la zone de Chat et remet le focus dessus
                    $('#zone_chat').scrollTop($('#zone_chat').offset().top);
                    return false; // Permet de bloquer l'envoi "classique" du formulaire
                });
            });

        },
        closeChat: function(event) {
            var self = this;
            event.stopImmediatePropagation();
            $('#chat').fadeOut(250, function() {
                $('#chat').removeClass('open');
                $('.chat-box').remove();
                require(['socket-io'], function(io) {
                    var socket = io.connect('http://my-events.local:8080');
                    socket.emit('leave_conversation', self.options.destinataire);
                });
            });
        },
        // Ajoute un message dans la page
        insereMessage: function(pseudo, message) {
            $('#zone_chat').append('<p><strong>' + pseudo + ': </strong> ' + message + '</p>');
        }

    }, {
        templateChat: _.template('\
            <div class="chat-box">\
                <div class="title"><%= destinataire %><span class="cross">x</span></div>\
                <div id="zone_chat">\
                </div>\
                <form action="/" method="post" id="formulaire_chat">\
                    <input type="text" name="message" id="message" placeholder="Votre message..." size="50" autofocus />\
                    <input type="submit" id="envoi_message" value="Envoyer" />\
                </form>\
           </div>\
        ')

    });

    return ListAmisView;
});

