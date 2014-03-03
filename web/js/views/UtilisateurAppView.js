define([
    'jquery',
    'underscore',
    'backbone',
    'user_view'
], function($, _, Backbone, UtilisateurView) {
    var UtilisateurAppView = Backbone.View.extend({
        el: '#utilisateur-list',
        events: {
        },
        initialize: function() {
            var collection = this.collection;
            collection.on('add', this.addOne, this);
            collection.on('reset', this.addAll, this);
            collection.on('all', this.render, this);
        },
        addAll: function() {
            this.$el.empty();
            this.collection.each(this.addOne, this);
        },
        addOne: function(item) {
            var view = new UtilisateurView({model: item});

            // Appelle le render pour injecter le code HTML des utilisateurs
            view.render();
        },
        render: function() {
            return this;
        }
    });

    return UtilisateurAppView;
});