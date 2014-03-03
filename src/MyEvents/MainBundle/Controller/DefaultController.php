<?php

namespace MyEvents\MainBundle\Controller;

use MyEvents\MainBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller {

	/**
	 * @var Utilisateur
	 */
	private $oUtilisateurConnecte = NULL;

	public function preExecute() {
		$oSecurityContext = $this->get('security.context');
		/* @var $oSecurityContext SecurityContext */
		if ($oSecurityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			$this->oUtilisateurConnecte = $oSecurityContext->getToken()->getUser();
		}
	}

	/**
	 * @Route("/", name="accueil", options={"expose"=true})
	 * @Template("MainBundle:Default:index.html.twig")
	 */
	public function accueilAction() {

		$toListeDemandeAmis = [];
		$toListeAmis = [];
		if (!is_null($this->oUtilisateurConnecte)) {
			$toListeDemandeAmis = $this->oUtilisateurConnecte->getListesDemandeAmiEnAttente($this->oUtilisateurConnecte);
			$toListeAmis = $this->oUtilisateurConnecte->getListeAmisValides();
		}

		$tListeDemandeAmis = [];
		foreach ($toListeDemandeAmis as $key => $oAmi) {
			/* @var $oAmi Utilisateur */
			$tListeDemandeAmis[] = [
			   'idDemandeAmi' => $key,
			   'idUtilisateur' => $oAmi->getId(),
			   'nom' => $oAmi->getNom(),
			   'prenom' => $oAmi->getPrenom(),
			   'username' => $oAmi->getUsername()
			];
		}

		$tAmis = [];
		foreach ($toListeAmis as $ami) {
			$tAmis[] = [
			   'id' => $ami->getId(),
			   'nom' => $ami->getNom(),
			   'prenom' => $ami->getPrenom(),
			   'username' => $ami->getUsername()
			];
		}

		return ['listeDemandeAmis' => $tListeDemandeAmis, 'listeAmis' => $tAmis];
	}
}
