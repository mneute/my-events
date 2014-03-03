<?php

namespace MyEvents\MainBundle\Controller;

use MyEvents\MainBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
	 */
	public function listeDemandeAmisAction() {

		$toListeDemandeAmis = array();
		$listeAmis = array();
		if ($this->oUtilisateurConnecte) {
			$toListeDemandeAmis = $this->oUtilisateurConnecte->getListesDemandeAmiEnAttente($this->oUtilisateurConnecte);
			$listeAmis = $this->oUtilisateurConnecte->getListeAmisValides($this->oUtilisateurConnecte);
		}

		$tListeDemandeAmis = array();
		foreach ($toListeDemandeAmis as $key => $oAmi) {
			$tListeDemandeAmis[] = array(
				'idDemandeAmi' => $key,
				'idUtilisateur' => $oAmi->getId(),
				'nom' => $oAmi->getNom(),
				'prenom' => $oAmi->getPrenom(),
				'username' => $oAmi->getUsername()
			);
		}

		$amis = array();
		foreach ($listeAmis as $ami) {
			$amis[] = array(
				'id' => $ami->getId(),
				'nom' => $ami->getNom(),
				'prenom' => $ami->getPrenom(),
				'username' => $ami->getUsername()
			);
		}

		return $this->render('MainBundle:Default:index.html.twig', array(
					'listeDemandeAmis' => $tListeDemandeAmis,
					'listeAmis' => $amis));
	}

}
