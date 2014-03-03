define([
    'jquery',
    'underscore',
    'backbone',
    'manif_view'
], function($, _, Backbone, ManifestationView) {
    var ManifestationAppView = Backbone.View.extend({
        el: '#contenu',
        events: {
            'click .newManif': 'openModalNewManif',
            'submit': 'submitManifestation'
        },
        initialize: function(options) {
            this.options = options;
            var collection = this.collection;
            collection.on('add', this.addOne, this);
            collection.on('reset', this.addAll, this);
            collection.on('all', this.render, this);
        },
        addAll: function() {
            this.$el.find('#manifestation-list').empty();
            this.collection.each(this.addOne, this);
        },
        addOne: function(item) {
            var view = new ManifestationView({model: item});

            // Appelle le render pour injecter le code HTML des utilisateurs
            view.render();
        },
        render: function() {
            return this;
        },
        openModalNewManif: function(e) {
            var viewForm = new ManifestationView();

            // Appelle le render du formulaire
            viewForm.renderForm(this.options.url);
        },
        submitManifestation: function(event) {
            event.preventDefault();
            var data = decodeURIComponent($("form").serialize());
            $.ajax({
                type: "POST",
                url: $('form').attr('action'),
                data: data
            }).done(function(msg) {
                if (msg == "ok") {
                    $('.formManif').modal('hide');
                    // TODO : Recharger la vue
                }
            });
            return false;
        }
    });

    return ManifestationAppView;
});