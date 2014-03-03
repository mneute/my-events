<?php

namespace MyEvents\MainBundle\Controller;

use MyEvents\MainBundle\Entity\Amis;
use MyEvents\MainBundle\Entity\AmisRepository;
use MyEvents\MainBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\SecurityContext;

class UtilisateurController extends Controller {

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
	 * @Route("/utilisateur", name="utilisateur", options={"expose"=true})
	 * @Template()
	 */
	public function utilisateurAction() {
		$oUtilisateurRepository = $this->getDoctrine()->getManager()->getRepository('MainBundle:Utilisateur');
		$toListeUtilisateurs = $oUtilisateurRepository->findAll();

		$tListeUtilisateurs = [];
		foreach ($toListeUtilisateurs as $oUtilisateur) {
			/* @var $oUtilisateur Utilisateur */
			$tListeUtilisateurs[] = [
				'id' => $oUtilisateur->getId(),
				'nom' => $oUtilisateur->getNom(),
				'prenom' => $oUtilisateur->getPrenom(),
				'adresse' => $oUtilisateur->getAdresse(),
				'email' => $oUtilisateur->getEmail()
			];
		}

		return ['listeUtilisateurs' => $tListeUtilisateurs];
	}

	/**
	 * @Route("/detail-utilisateur/{id}", name="detail-utilisateur", options={"expose"=true}, defaults={"id" = 1})
	 * @Template()
	 */
	public function detailUtilisateurAction(Utilisateur $oUtilisateur, Request $oRequest) {
		if (is_null($this->oUtilisateurConnecte)) {
			throw new HttpException(401, 'Veuillez vous connecter.');
		} else {
			if (!$oRequest->isXmlHttpRequest()) {
				return $this->redirect($this->generateUrl('utilisateur'));
			} else {
				return ['utilisateur' => $oUtilisateur];
			}
		}
	}

	/**
	 * @Route("/demande-ami/{id}", name="demande-ami", options={"expose"=true})
	 */
	public function demanderEnAmiAction(Request $oRequest, Utilisateur $oDestinataire) {
		if (is_null($this->oUtilisateurConnecte)) {
			throw new HttpException(401, 'Veuillez vous connecter.');
		} else {
			if (!$oRequest->isXmlHttpRequest()) {
				return $this->redirect($this->generateUrl('utilisateur'));
			} else {
				$oAmisRepository = $this->getDoctrine()->getManager()->getRepository('MainBundle:Amis');
				/* @var $oAmisRepository AmisRepository */
				$oAmisRepository->nouvelleDemandeAmi($this->oUtilisateurConnecte, $oDestinataire);

				return new JsonResponse(['success' => TRUE]);
			}
		}
	}

	/**
	 * @Route("/repondre-demande/{id}", name="repondre-demande", options={"expose"=true}, defaults={"id" = 1})
	 * @Method({"POST"})
	 */
	public function repondreALaDemandeAmiAction(Request $oRequest, Amis $oAmi) {
		if (is_null($this->oUtilisateurConnecte)) {
			throw new HttpException(401, 'Veuillez vous connecter.');
		} else {
			if (!$oRequest->isXmlHttpRequest()) {
				return $this->redirect($this->generateUrl('accueil'));
			} else {
				if ($oAmi->getUtilisateurDestinataire() == $this->oUtilisateurConnecte) {
					$valide = json_decode($oRequest->request->get('reponse'));
					$oAmi->setValide($valide);
					$oAmi->setDateReponse(new \DateTime);

					$this->getDoctrine()->getManager()->flush();

					return new JsonResponse(['success' => true]);
				} else {
					throw new HttpException(403, 'Vous n\'avez pas accès a cette demande');
				}
			}
		}
	}

}
