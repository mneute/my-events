<?php

namespace MyEvents\MainBundle\Controller;

use FOS\UserBundle\Doctrine\UserManager;
use MyEvents\MainBundle\Entity\Utilisateur;
use MyEvents\MainBundle\Form\Type\UtilisateurType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecuriteController extends Controller {

	/**
	 * @var UserManager
	 */
	private $oUserManager;

	public function preExecute() {
		$this->oUserManager = $this->get('fos_user.user_manager');
	}

	/**
	 * @Route("/connexion", name="connexion", options={"expose"=true})
	 * @Template()
	 */
	public function connexionAction(Request $oRequest) {
		$oSession = $oRequest->getSession();

		// get the error if any (works with forward and redirect -- see below)
		if ($oRequest->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = 'Login ou mot de passe incorrect';
		} elseif (null !== $oSession && $oSession->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = 'Login ou mot de passe incorrect';
			$oSession->remove(SecurityContext::AUTHENTICATION_ERROR);
		} else {
			$error = '';
		}
		// last username entered by the user
		$lastUsername = (null === $oSession) ? '' : $oSession->get(SecurityContext::LAST_USERNAME);
		$csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
		$oSession->getFlashBag()->add('notice', $error);
		$referer = $oRequest->server->get('HTTP_REFERER');
		if (is_null($referer) || $referer === '') {
			$referer = $this->generateUrl('accueil');
		} else if (strpos($referer, $oRequest->getSchemeAndHttpHost()) === FALSE) {
			// lien externe au site
			$referer = $this->generateUrl('accueil');
		}

		$tRetour = array('lastUsername' => $lastUsername, 'csrf_token' => $csrfToken, 'referer' => $referer);
		if ($oRequest->isXmlHttpRequest()) {
			return $this->render('MainBundle:Securite:connexion-ajax.html.twig', $tRetour);
		} else {
			return $tRetour;
		}
	}

	/**
	 * @Route("/verifie_login", name="verifie_login", options={"expose"=true})
	 */
	public function verifieLoginAction() {
		throw new \Exception('Votre router est mal configuré');
	}

	/**
	 * @Route("/deconnexion", name="deconnexion", options={"expose"=true})
	 */
	public function deconnexionAction() {
		throw new \Exception('Votre router est mal configuré');
	}

	/**
	 * @Route("/inscription", name="inscription", options={"expose"=true})
	 * @Template()
	 */
	public function inscriptionAction(Request $oRequest) {
		$oUtilisateur = $this->oUserManager->createUser();
		/* @var $oUtilisateur Utilisateur */

		$oForm = $this->createForm(new UtilisateurType(), $oUtilisateur);

		if ($oRequest->isMethod('POST')) {
			$oForm->submit($oRequest);
			if ($oForm->isValid()) {
				if ($this->traitementFormulaireInscription($oForm)) {
					$this->connecterUtilisateur($oUtilisateur);
					return $this->redirect($this->generateUrl('accueil'));
				}
			}
		}

		return array('form' => $oForm->createView());
	}

	/**
	 * Traite le formulaire d'inscription d'un utilisateur
	 * @param \Symfony\Component\Form\Form $oForm
	 * @return boolean TRUE si tout va bien, FALSE si des erreurs sont présentes
	 */
	private function traitementFormulaireInscription(Form $oForm) {
		$password = $oForm->get('plainPassword')->getData();
		$repeatPassword = $oForm->get('repeatPassword')->getData();

		if ($password !== $repeatPassword) {
			$oForm->get('repeatPassword')->addError(new FormError('Les mots de passe ne sont pas identiques'));
			return FALSE;
		} else {
			$oUtilisateur = $oForm->getData();
			/* @var $oUtilisateur Utilisateur */
			$oUtilisateur->setCredentialsExpired(FALSE);
			$oUtilisateur->setEnabled(TRUE);
			$oUtilisateur->setExpired(FALSE);
			$oUtilisateur->setLocked(FALSE);

			$this->oUserManager->updateUser($oUtilisateur);
			return TRUE;
		}
	}

	/**
	 * Fonction permettant de connecter l'utilisateur après une inscription réussie
	 * @param \MyEvents\MainBundle\Entity\Utilisateur $oUtilisateur
	 */
	private function connecterUtilisateur(Utilisateur $oUtilisateur) {
		$oToken = new UsernamePasswordToken($oUtilisateur, $oUtilisateur->getPassword(), 'main', $oUtilisateur->getRoles());
		$this->get('security.context')->setToken($oToken);

		// Fire the login event
		// Logging the user in above the way we do it doesn't do this automatically
		$event = new InteractiveLoginEvent($this->getRequest(), $oToken);
		$this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
	}

}
