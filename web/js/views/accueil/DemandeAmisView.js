define([
    'jquery',
    'underscore',
    'backbone'
], function($, _, Backbone) {
    var DemandeAmisView = Backbone.View.extend({
        el: '#demande-amis-list',
        events: {
            'click .detail-utilisateur': 'renderDetail',
            'click .amis.reponse': 'reponseAmi'
        },
        initialize: function(options) {
            var self = this;
            this.collection = options.collection;
            this.listAmis = options.listAmis;
            this.collection.bind('change', self.render, this);
            this.collection.bind('destroy', self.remove, this);

            this.template = _.template($('#demande-amis-list-template').html());
        },
        render: function() {
            var self = this;
            if (self.collection > 0) {
                this.$el.html(self.template({amis: self.collection}));
            }

            return this;
        },
        renderDetail: function() {
            define(['user_view'], function(UtilisateurView) {
                var view = new UtilisateurView();
                view.openModal();
            });
        },
        reponseAmi: function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            var $element = $(event.target),
                    lien = $element.attr('href'),
                    reponse = $element.data('response'),
                    idDemande = $element.data('demande'),
                    self = this;
            ;

            $.ajax({
                type: 'POST',
                url: lien,
                data: {'reponse': Boolean(reponse)},
                success: function(data) {
                    if (data.success) {
                        if (self.collection.origModels === undefined) {
                            self.collection.origModels = _.clone(self.collection.models);
                        }
                        _.each(self.collection.models, function(model) {
                            if (model.attributes.idDemandeAmi == idDemande) {
                                self.collection.remove(model);
                                self.newFriends = model;
                            }
                        });
                        self.render();
                        require(['list_amis_view'], function(ListAmisView) {
                            self.listAmis.add(self.newFriends);
                            var amisView = new ListAmisView({collection: self.listAmis});
                            amisView.render();
                        });
                    }
                }
            });
        }
    });

    return DemandeAmisView;
});

