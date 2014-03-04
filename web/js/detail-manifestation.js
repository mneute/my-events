require([
	'jquery'
], function($) {
	var map;
	var geocoder;
	var drawingManager;
	var centreMap;
	var marqueurs = {};
	var idManif = $('#manifestation-id').val();
	var infobulle;

	$(function() {
		var $divMap = $('#map-manifestation');
		creerMap($divMap);
		var $divAdresse = $('#manifestation-Lieu');
		geocodeAdresse($divAdresse);
		chargerMarqueurs();
		creerMarqueurs();
		gererParticipations();
	});

	/**
	 * Créé la map et la centre sur Paris
	 * @param {jQuery} $divMap
	 */
	function creerMap($divMap) {
		centreMap = new google.maps.LatLng(48.1159155, -1.6884545);
		var mapOptions = {
			center: centreMap,
			zoom: 15,
			mapTypeId: google.maps.MapTypeId.HYBRID
		};
		map = new google.maps.Map($divMap[0], mapOptions);
	}

	/**
	 * Récupère l'adresse affichée sur la page (via l'objet jquery passé en paramètre) et la transforme en coordonnées GPS (geocoding),
	 * puis centre la map sur le marqueur ainsi créé.
	 * @param {jQuery} $divAdresse Objet jQuery pointant sur la div contenant l'adresse
	 */
	function geocodeAdresse($divAdresse) {
		geocoder = new google.maps.Geocoder();
		geocoder.geocode({address: $divAdresse.text(), region: 'fr'}, function(results, status) {
			if (status === google.maps.GeocoderStatus.OK) {
				centreMap = results[0].geometry.location;
				var marker = new google.maps.Marker({
					map: map,
					position: centreMap,
					animation: google.maps.Animation.DROP,
					type: 'principal',
					title: 'Point de rendez-vous'
				});
				marqueurs['origine'] = marker;
				// décommenter la ligne ci-dessous pour ajouter une infobulle sur le marqueur principal
				ajouterEvenementAuMarqueur(marker);
			} else {
				$divAdresse.addClass('text-danger').find('span').css('display', 'inline-block');
			}
			map.setCenter(centreMap);
		});
	}

	/**
	 * Chargement en AJAX de la liste des marqueurs de l'évènement
	 */
	function chargerMarqueurs() {
		$.ajax({
			url: Routing.generate('liste-marqueurs', {id: idManif}),
			method: 'GET',
			success: function(data) {
				$.each(data.marqueurs, function(id, marqueur) {
					var pos = new google.maps.LatLng(marqueur.latitude, marqueur.longitude);
					var marker = new google.maps.Marker({
						map: map,
						position: pos,
						animation: google.maps.Animation.DROP,
						type: marqueur.type,
						id: id,
						draggable: data.editable
					});
					marqueurs[marker.id] = marker;
					ajouterEvenementAuMarqueur(marker);
				});
			}
		});
	}

	/**
	 * Gestion du clic sur le bouton "ajouter un marqueur"
	 */
	function creerMarqueurs() {
		$('#add-marker').click(function(event) {
			event.preventDefault();

			drawingManager = new google.maps.drawing.DrawingManager({
				drawingMode: google.maps.drawing.OverlayType.MARKER,
				drawingControl: false,
				editable: true,
				markerOptions: {
					draggable: true
				}
			});
			drawingManager.setMap(map);

			google.maps.event.addListener(drawingManager, 'markercomplete', function(marker) {
				// lorsque le marqueur est placé
				var lat = marker.getPosition().lat();
				var lng = marker.getPosition().lng();

				$.ajax({
					url: Routing.generate('creer-marqueur', {id: idManif}),
					method: 'POST',
					data: {
						latitude: lat,
						longitude: lng
					},
					success: function(data) {
						if (!data.success) {
							alert(data.message);
						} else {
							marker.id = data.marqueur.id;

							marqueurs[marker.id] = marker;
							ajouterEvenementAuMarqueur(marker);
						}
					}
				});

				drawingManager.setMap(null);
			});
		});
	}

	/**
	 * Ajoute les évènements 'click' et 'dragend' au marqueur
	 * @param {google.maps.Marker} marqueur
	 */
	function ajouterEvenementAuMarqueur(marqueur) {
		google.maps.event.addListener(marqueur, 'click', afficherInfoBulle);
		google.maps.event.addListener(marqueur, 'dragend', deplacerMarqueur);
	}

	/**
	 * Affichage de l'infobulle au clic sur un marqueur (chargement AJAX)
	 */
	var afficherInfoBulle = function() {
		var marqueur = this;
		map.panTo(marqueur.getPosition());
		if (marqueur.id !== undefined) {
			$.ajax({
				url: Routing.generate('get-info-bulle', {id: marqueur.id}),
				method: 'GET',
				success: function(data) {
					if (infobulle !== undefined && infobulle.map !== undefined) {
						infobulle.close();
					}
					infobulle = new google.maps.InfoWindow({
						content: data
					});
					infobulle.open(map, marqueur);
					google.maps.event.addListener(infobulle, 'domready', supprimerMarqueur);
					google.maps.event.addListener(infobulle, 'domready', editerMarqueur);
				}
			});
		}
	};

	/**
	 * Gestion des évènements de drag 'n drop des marqueurs
	 */
	var deplacerMarqueur = function() {
		var marqueur = this;
		if (marqueur.id !== undefined) {
			$.ajax({
				url: Routing.generate('deplacer-marqueur', {id: marqueur.id}),
				method: 'POST',
				data: {
					latitude: marqueur.getPosition().lat(),
					longitude: marqueur.getPosition().lng()
				},
				success: function(data) {
					if (!data.success) {
						alert(data.message);
					}
				}
			});
		}
	};

	/**
	 * Suppression d'un marqueur au clic sur le bouton 'supprimer' dans l'infobulle
	 */
	var supprimerMarqueur = function() {
		var infobulle = this;
		var marqueur = infobulle.anchor;

		var $divSupprimer = $('#supprimer-marqueur');
		var $divConfirmer = $('#confirmer-suppression-marqueur');
		$divSupprimer.click(function(event) {
			event.preventDefault();
			$divConfirmer.css('display', 'inline-block');
		});

		$divConfirmer.click(function(event) {
			event.preventDefault();

			$.ajax({
				url: Routing.generate('supprimer-marqueur', {id: marqueur.id}),
				method: 'POST',
				success: function(data) {
					if (data.success) {
						marqueur.setMap(null);
						delete marqueurs[marqueur.id];
					} else {
						alert(data.message);
					}
				}
			});
		});
	};

	/**
	 * Faire apparaître une modal Bootstrap pour éditer un marqueur
	 */
	var editerMarqueur = function() {
		var infobulle = this;
		var marqueur = infobulle.anchor;
		var $modal = $('#editMarqueur');

		$('#editer-marqueur').click(function(event) {
			event.preventDefault();

			$modal.find('.modal-content').load(Routing.generate('editer-marqueur', {id: marqueur.id}), function() {
				$modal.modal('show');

				$modal.on('shown.bs.modal', function() {
					soumissionFormulaireEditionMarqueur($modal, marqueur);
				});
			});
		});

	};

	/**
	 * Soumettre le formulaire d'édition
	 * @param {jQuery} $modal
	 * @param {google.maps.Marker} marqueur
	 */
	function soumissionFormulaireEditionMarqueur($modal, marqueur) {
		$('#valider-marqueur').click(function(event) {
			event.preventDefault();
			var $form = $('#editMarqueur').find('form');

			$.ajax({
				url: Routing.generate('editer-marqueur', {id: marqueur.id}),
				method: 'POST',
				data: $form.serialize(),
				success: function(data) {
					if (!data.success) {
						alert(data.message);
					} else {
						$modal.modal('hide');
						google.maps.event.trigger(marqueur, 'click');
					}
				}
			});
		});
	}

	/**
	 * Gère les clics sur les boutons de confirmation de participation à l'évènement
	 */
	function gererParticipations() {
		$('#confirm-participation').click(function(event) {
			event.preventDefault();

			$.ajax({
				url: Routing.generate('participer-manifestation', {id: idManif}),
				method: 'POST',
				success: function(data) {
					if (data.success) {
						window.location.href = data.location;
					} else {
						alert(data.message);
					}
				}
			});
		});
		$('#confirm-organisation').click(function(event) {
			event.preventDefault();

			$.ajax({
				url: Routing.generate('organiser-manifestation', {id: idManif}),
				method: 'POST',
				success: function(data) {
					if (data.success) {
						window.location.href = data.location;
					} else {
						alert(data.message);
					}
				}
			});
		});
	}
});