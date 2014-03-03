define(['backbone', 'user_model'], function(Backbone, UtilisateurModel) {
	var DemandeAmisCollection = Backbone.Collection.extend({
		model: UtilisateurModel
	});

	return DemandeAmisCollection;
});
