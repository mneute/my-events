define([
    'backbone',
    'underscore',
    'jquery'
], function(
        Backbone,
        _,
        $
        ) {
    var UtilisateurNavigationView = Backbone.View.extend({
        el: '.utilisateur',
        events: {
            'click .membre': 'updateSortBy'
        },
        initialize: function() {

        },
        updateSortBy: function(e) {
            var $target = e.target;
            e.preventDefault();
            var currentSort = $($target).data('attribute');
            this.collection.pager(currentSort, 'asc');
        },
    });

    return UtilisateurNavigationView;
});

