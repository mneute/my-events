define([
    'jquery',
    'underscore',
    'backbone'
], function($, _, Backbone) {
    var map;
    var centreMap;
    var geocoder;

    var UtilisateurView = Backbone.View.extend({
        el: '.utilisateur',
        events: {
            'click .openModal': 'openModal'
        },
        initialize: function() {
            this.user = this.model;
            this.user.bind('change', this.render, this);
            this.user.bind('destroy', this.remove, this);
        },
        render: function() {
            this.template = _.template($('#utilisateur-list-template').html());
            this.$el.append(this.template({user: this.user.toJSON()}));

            return this;
        },
        openModal: function(event) {
            event.preventDefault();
            event.stopImmediatePropagation(); // pour éviter que l'évènement ne soit appelé X fois (X étant le nombre d'éléments du DOM concernés par le sélecteur)
            var element = $(event.target); // balise <a>
            var lien = element.attr('href');
            var modal = $('#editUtilisateur');

            modal.find('.modal-content').load(lien, function() {
                modal.modal('show');
                modal.on('shown.bs.modal', function() {
                    afficherMap();
                    demandeAmi();
                });
            });
        }
    });

    function afficherMap() {
        centreMap = new google.maps.LatLng(48.8588589, 2.3470599); // Paris
        var mapOptions = {
            center: centreMap,
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.HYBRID
        };
        var $divMap = $('#map-utilisateur');
        map = new google.maps.Map($divMap[0], mapOptions);
        geocoder = new google.maps.Geocoder();
        var $adresse = $('#utilisateur-Adresse');
        geocoder.geocode({address: $adresse.text(), region: 'fr'}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                centreMap = results[0].geometry.location;
                var marker = new google.maps.Marker({
                    map: map,
                    position: centreMap,
                    animation: google.maps.Animation.DROP
                });
            } else {
                $adresse.addClass('text-danger').find('span').css('display', 'inline-block');
            }
            map.setCenter(centreMap);
        });
    }

    function demandeAmi() {
        var $btnDemanderAmi = $('#demander-ami');
        if ($btnDemanderAmi.length !== 0) {
            $btnDemanderAmi.click(function(event) {
                event.preventDefault();

                $.ajax({
                    url: Routing.generate('demande-ami', {id: $('#idUtilisateur').val()}),
                    success: function(data) {
                        if (data.success) {
                            $btnDemanderAmi.addClass('disabled').text('Demande en attente de réponse');
                        } else {
                            alert('Un problème est survenue lors de la demande. Veuillez réessayer plus tard.');
                        }
                    }
                });
            });
        }
    }

    return UtilisateurView;
});