define([
    'backbone',
    'underscore',
    'jquery'
], function(
        Backbone,
        _,
        $
        ) {
    var ManifestationNavigationView = Backbone.View.extend({
        el: '.manifestation',
        events: {
            'click .manif': 'updateSortBy'
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

    return ManifestationNavigationView;
});

