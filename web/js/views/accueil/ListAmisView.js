define([
    'jquery',
    'underscore',
    'backbone',
    'app_view'
], function($, _, Backbone, AppView) {
    var ListAmisView = Backbone.View.extend({
        el: '#amis-list',
        events: {
            'click .chat': 'openChat'
        },
        initialize: function(options) {
            var self = this;
            if (options) {
                this.collection = options.collection;
                this.collection.bind('change', self.render, this);
                this.collection.bind('destroy', self.remove, this);
            }
            this.template = _.template($('#amis-list-template').html());
        },
        render: function() {
            var self = this;

            if (self.collection.length > 0) {
                this.$el.html(self.template({amis: self.collection}));
            }

            return this;
        },
        openChat: function(event) {
            var $element = event.target,
                    destinataire = $($element).data('destinataire'),
                    destinataireId = $($element).data('id'),
                    username = $("#amis-list").data('utilisateur');

            var view = new AppView({destinataire: destinataire, room: username + '_' + destinataire, destinataireId: destinataireId});
            view.render();
        },
        updateListAmis: function(newFriends) {

            console.log(newFriends);
        }

    });

    return ListAmisView;
});

