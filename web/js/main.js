require.config({
    paths: {
        'jquery': 'lib/jquery-2.1.0.min',
        'underscore': 'lib/underscore-min',
        'backbone': 'lib/backbone-min',
        'backbone-paginator': 'lib/backbone.paginator',
        'bootstrap': 'lib/bootstrap.min',
        'bootstrap-datepicker': 'lib/bootstrap-datepicker',
        'socket-io': 'node_modules/socket.io/node_modules/socket.io-client/dist/socket.io',
        // Models
        'user_model': 'models/UtilisateurModel',
        'manif_model': 'models/ManifestationModel',
        // Collections
        'user_paginated_collection': 'collections/UtilisateurPaginatedCollection',
        'manif_paginated_collection': 'collections/ManifestationPaginatedCollection',
        'amis_collection': 'collections/AmisCollection',
        // Views
        'user_app_view': 'views/UtilisateurAppView',
        'user_view': 'views/utilisateurs/UtilisateurView',
        'user_pagination_view': 'views/utilisateurs/UtilisateurPaginationView',
        'user_navigation_view': 'views/utilisateurs/UtilisateurNavigationView',
        'manif_app_view': 'views/ManifestationAppView',
        'manif_view': 'views/manifestations/ManifestationView',
        'manif_pagination_view': 'views/manifestations/ManifestationPaginationView',
        'manif_navigation_view': 'views/manifestations/ManifestationNavigationView',
        'accueil_app_view': 'views/AccueilAppView',
        'demande_amis_view': 'views/Accueil/DemandeAmisView',
        'list_amis_view': 'views/Accueil/ListAmisView',
        'app_view': 'views/AppView'
    },
    shim: {
        'underscore': {
            deps: [],
            exports: '_'
        },
        'backbone': {
            deps: ['jquery', 'underscore'],
            exports: 'Backbone'
        },
        'backbone-paginator': {
            deps: ['underscore', 'jquery', 'backbone'],
            exports: 'Backbone.Paginator'
        },
        'bootstrap': {
            deps: ['jquery'],
            exports: 'Bootstrap'
        },
        'bootstrap-datepicker': {
            deps: ['jquery', 'bootstrap'],
            exports: 'Datepicker'
        }
    }
});
require(['jquery', 'backbone', 'underscore', 'app_view', 'amis_collection', 'bootstrap'],
        function($, Backbone, _, AppView) {
            $(function() {
                var divUser = $('#user');
                var contenuDivUser = divUser.html();
                $('#connexion').click(function(event) {
                    event.preventDefault();

                    var url = $(this).attr('href');
                    $.ajax({
                        url: url,
                        dataType: 'html',
                        success: function(data) {

                            divUser.css('width', '700px');

                            // Affichage du formulaire de connexion à la fin de l'animation
                            setTimeout(function() {
                                divUser.html(data);
                                $('#annuler-login').click(function(event) {
                                    event.preventDefault();
                                    divUser.html(contenuDivUser).css('width', '300px');
                                });
                            }, 400);

                        },
                        error: function() {
                            alert('Une erreur est survenue.<br/>Veuillez réessayer plus tard.');
                        }
                    });
                });

                // Quand on reçoit un message, on génère la vue l'insère dans la page
                require(['socket-io'], function(io) {
                    var socket = io.connect('http://my-events.local:8080');

                    // Prevent user this other one has left the conversation
                    socket.on('msg_user_leave', function(msg) {
                        $('#zone_chat').append('<p><em>' + msg + ': </em></p>');
                        $('#zone_chat').scrollTop($('#zone_chat').offset().top);
                    });

                    // Connexion de l'utilisateur dès qu'il reçoit un message
                    socket.on('chatConnect', function(usr) {
                        socket.emit('adduser', usr);
                    });

                    // affiche automatiquement le message envoyé par un ami
                    socket.on('msg_user_handle', function(username, pseudo, data, destinataireId) {
                        var listChatAmisView = new AppView({destinataire: pseudo, room: username + '_' + pseudo, destinataireId: destinataireId});

                        listChatAmisView.render();
                        insereMessage(pseudo, data);
                        $('#zone_chat').scrollTop($('#zone_chat').offset().top);
                    });

                    // Ajoute un message dans la page
                    function insereMessage(pseudo, message) {
                        $('#zone_chat').append('<p><strong>' + pseudo + ': </strong> ' + message + '</p>');
                    }
                });
            });
        }
);