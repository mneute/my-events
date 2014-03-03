define(['backbone',
    'underscore',
    'jquery',
    'user_model'
], function(Backbone, _, $, UtilisateurModel) {
    var DemandeAmisCollection = Backbone.Collection.extend({
        model: UtilisateurModel
    });

    return DemandeAmisCollection;
});
