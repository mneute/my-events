define([
    'jquery',
    'underscore',
    'backbone',
    'bootstrap-datepicker'
], function($, _, Backbone, datepicker) {
    var ManifestationView = Backbone.View.extend({
        el: '.manifestation',
        initialize: function() {
            this.manif = this.model;
//            this.manif.bind('change', this.render, this);
//            this.manif.bind('destroy', this.remove, this);
        },
        render: function() {
            this.template = _.template($('#manifestation-list-template').html());
            this.$el.append(this.template({manif: this.manif.toJSON()}));

            return this;
        },
        renderForm: function(url) {
            var self = this;
            $('.formManif').load(url, function() {
                self.templateForm = _.template($('#form-new-manifestation-template').html());
                $('.datepicker').datepicker({
                    format: "dd/mm/yyyy"
                });
                $(this).append(self.templateForm({url: url}));
            });
            return this;
        }
    });

    return ManifestationView;
});