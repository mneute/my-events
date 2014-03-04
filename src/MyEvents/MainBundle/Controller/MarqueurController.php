<?php

namespace MyEvents\MainBundle\Controller;

use Doctrine\ORM\EntityManager;
use MyEvents\MainBundle\Entity\Manifestation;
use MyEvents\MainBundle\Entity\Marqueur;
use MyEvents\MainBundle\Entity\TypeMarqueur;
use MyEvents\MainBundle\Entity\Utilisateur;
use MyEvents\MainBundle\Form\Type\MarqueurType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Route("/marqueurs")
 */
class MarqueurController extends Controller {

	/**
	 * @var Utilisateur
	 */
	private $oUtilisateurConnecte = NULL;

	/**
	 * @var EntityManager
	 */
	private $oManager = NULL;

	public function preExecute() {
		$oSecurityContext = $this->get('security.context');
		/* @var $oSecurityContext SecurityContext */
		if ($oSecurityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			$this->oUtilisateurConnecte = $oSecurityContext->getToken()->getUser();
		}

		$this->oManager = $this->getDoctrine()->getManager();
	}

	/**
	 * @Route("/liste/{id}", name="liste-marqueurs", options={"expose"=true})
	 */
	public function getListeMarqueursPourManifestationAction(Request $oRequest, Manifestation $oManifestation) {
		if (!$oRequest->isXmlHttpRequest()) {
			return $this->redirect($this->generateUrl('detail-manifestation', array('id' => $oManifestation->getId())));
		}
		$tTemp = $oManifestation->getMarqueurs();
		$toMarqueurs = [];
		foreach ($tTemp as $oMarqueur) {
			/* @var $oMarqueur Marqueur */
			$toMarqueurs[$oMarqueur->getId()] = [
				'nom' => $oMarqueur->getNom(),
				'type' => $oMarqueur->getTypeMarqueur()->getLibelle(),
				'latitude' => $oMarqueur->getLatitude(),
				'longitude' => $oMarqueur->getLongitude()
			];
		}

		return new JsonResponse(['marqueurs' => $toMarqueurs, 'editable' => $oManifestation->peutEtreEditePar($this->oUtilisateurConnecte)]);
	}

	/**
	 * @Route("/creer/{id}", name="creer-marqueur", options={"expose"=true})
	 * @Method({"POST"})
	 */
	public function creerMarqueurPourManifestationAction(Request $oRequest, Manifestation $oManifestation) {
		if (!$oRequest->isXmlHttpRequest()) {
			return $this->redirect($this->generateUrl('detail-manifestation', array('id' => $oManifestation->getId())));
		}
		if ($oManifestation->peutEtreEditePar($this->oUtilisateurConnecte)) {
			$tCoordonnees = $this->verifiePresenceLatitudeLongitude($oRequest);
			$oTypeMarqueurRepo = $this->oManager->getRepository('MainBundle:TypeMarqueur');
			$oTypeMarqueur = $oTypeMarqueurRepo->findOneBy(['libelle' => 'POI']);
			/* @var $oTypeMarqueur TypeMarqueur */

			$oMarqueur = new Marqueur();
			$oMarqueur
					->setNom($oManifestation->getNom())
					->setLatitude($tCoordonnees['latitude'])
					->setLongitude($tCoordonnees['longitude'])
					->setDateDebut($oManifestation->getDateDebut())
					->setDateFin($oManifestation->getDateFin())
					->setTypeMarqueur($oTypeMarqueur)
					->setManifestation($oManifestation);

			$this->oManager->persist($oMarqueur);
			$this->oManager->flush();

			return new JsonResponse(['success' => TRUE, 'marqueur' => ['id' => $oMarqueur->getId()]]);
		} else {
			return new JsonResponse(['success' => FALSE, 'message' => 'Vous n\'avez pas le droit de créer de marqueur pour cet évènement.']);
		}
	}

	/**
	 * @Route("/{id}/deplacer", name="deplacer-marqueur", options={"expose"=true})
	 */
	public function deplacerAction(Request $oRequest, Marqueur $oMarqueur) {
		if (!$oRequest->isXmlHttpRequest()) {
			return $this->redirect($this->generateUrl('detail-manifestation', ['id' => $oMarqueur->getManifestation()->getId()]));
		}
		if ($oMarqueur->peutEtreEditePar($this->oUtilisateurConnecte)) {
			$tCoordonnees = $this->verifiePresenceLatitudeLongitude($oRequest);

			$oMarqueur->setLatitude($tCoordonnees['latitude'])->setLongitude($tCoordonnees['longitude']);
			$this->oManager->flush();

			return new JsonResponse(['success' => TRUE]);
		} else {
			return new JsonResponse(['success' => FALSE, 'message' => 'Vous n\'avez pas le droit de modifier ce marqueur.']);
		}
	}

	/**
	 * @Route("/{id}/supprimer", name="supprimer-marqueur", options={"expose"=true})
	 */
	public function supprimerMarqueurAction(Request $oRequest, Marqueur $oMarqueur) {
		if (!$oRequest->isXmlHttpRequest()) {
			return $this->redirect($this->generateUrl('detail-manifestation', ['id' => $oMarqueur->getManifestation()->getId()]));
		}
		if ($oMarqueur->peutEtreEditePar($this->oUtilisateurConnecte)) {
			$this->oManager->remove($oMarqueur);
			$this->oManager->flush();

			return new JsonResponse(['success' => TRUE]);
		} else {
			return new JsonResponse(['success' => FALSE, 'message' => 'Vous n\'avez pas le droit de supprimer ce marqueur.']);
		}
	}

	/**
	 * @Route("/{id}/info-bulle", name="get-info-bulle", options={"expose"=true})
	 * @Template()
	 */
	public function recupererInfoBullePourMarqueurAction(Request $oRequest, Marqueur $oMarqueur) {
		if (!$oRequest->isXmlHttpRequest()) {
			return $this->redirect($this->generateUrl('detail-manifestation', ['id' => $oMarqueur->getManifestation()->getId()]));
		}
		return ['marqueur' => $oMarqueur];
	}

	/**
	 * @Route("/{id}/editer", name="editer-marqueur", options={"expose"=true})
	 * @Template()
	 */
	public function editerMarqueurAction(Request $oRequest, Marqueur $oMarqueur) {
		if (!$oRequest->isXmlHttpRequest()) {
			return $this->redirect($this->generateUrl('detail-manifestation', ['id' => $oMarqueur->getManifestation()->getId()]));
		}

		$oForm = $this->createForm(new MarqueurType(), $oMarqueur);

		if ($oRequest->isMethod('POST')) {
			$oForm->submit($oRequest);
			if ($oForm->isValid()) {
				$this->oManager->flush();

				return new JsonResponse(['success' => true, 'marqueur' => ['nom' => $oMarqueur->getNom()]]);
			} else {
				return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la validation du formulaire']);
			}
		}

		return ['form' => $oForm->createView(), 'marqueur' => $oMarqueur];
	}

	/**
	 * Vérifie que les paramètres "latitude" et "longitude" sont présent dans le corps de la requête (POST).
	 * S'ils ne sont pas présents, une <code>HttpException</code> est levée.
	 * @param Request $oRequest
	 * @return array Tableau avec les indices "latitude" et "longitude"
	 */
	private function verifiePresenceLatitudeLongitude(Request $oRequest) {
		if (is_null($latitude = $oRequest->request->get('latitude')) & is_null($longitude = $oRequest->request->get('longitude'))) {
			throw new HttpException(400, 'Bad request');
		}
		return ['latitude' => $latitude, 'longitude' => $longitude];
	}
}
