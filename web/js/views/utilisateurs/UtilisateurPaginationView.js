define([
    'backbone',
    'underscore',
    'jquery'
], function(
        Backbone,
        _,
        $
        ) {
    var UtilisateurPaginationView = Backbone.View.extend({
        el: '#utilisateur-pagination',
        events: {
            'click a.first': 'gotoFirst',
            'click a.prev': 'gotoPrev',
            'click a.next': 'gotoNext',
            'click a.last': 'gotoLast',
            'click a.page': 'gotoPage',
            'click .howmany a': 'changeCount'
        },
        initialize: function() {
            this.template = _.template($('#utilisateur-pagination-template').html());
            this.collection.on('reset', this.render, this);
            this.collection.on('add', this.render, this);
        },
        render: function() {
            this.$el.html(this.template(this.collection.info()));
            return this;
        },
        gotoFirst: function(e) {
            e.preventDefault();
            var self = this;
            self.collection.goTo(1);
        },
        gotoPrev: function(e) {
            e.preventDefault();
            var self = this;
            self.collection.previousPage();
        },
        gotoNext: function(e) {
            e.preventDefault();
            var self = this;
            self.collection.nextPage();
        },
        gotoLast: function(e) {
            e.preventDefault();
            var self = this;
            self.collection.goTo(self.collection.information.lastPage);
        },
        gotoPage: function(e) {
            e.preventDefault();
            var self = this;
            var page = $(e.target).text();
            self.collection.goTo(page);
        },
        changeCount: function(e) {
            e.preventDefault();
            var self = this;
            var per = $(e.target).text();
            self.collection.howManyPer(per);
        }
    });
    return UtilisateurPaginationView;
});



