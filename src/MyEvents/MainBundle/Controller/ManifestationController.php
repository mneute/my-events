<?php

namespace MyEvents\MainBundle\Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use MyEvents\MainBundle\Entity\Manifestation;
use MyEvents\MainBundle\Entity\Organisation;
use MyEvents\MainBundle\Entity\Participation;
use MyEvents\MainBundle\Entity\Utilisateur;
use MyEvents\MainBundle\Form\Type\ManifestationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Route("/manifestation")
 */
class ManifestationController extends Controller {

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
	 * @Route("/liste", name="manifestation", options={"expose"=true})
	 */
	public function getManifestationsAction() {
		$oManifestationRepo = $this->oManager->getRepository('MainBundle:Manifestation');
		$toManifestations = $oManifestationRepo->findAll();
		$listeManifestations = array();

		foreach ($toManifestations as $manifestation) {
			if ($manifestation->getDateDebut() > new DateTime()) {
				$manif['id'] = $manifestation->getId();
				$manif['nom'] = $manifestation->getNom();
				$manif['dateDebut'] = $manifestation->getDateDebut()->format('d-m-Y');
				$manif['dateFin'] = $manifestation->getDateFin()->format('d-m-Y');
				$manif['adresse'] = $manifestation->getAdresse();

				$listeManifestations[] = $manif;
			}
		}

		$urlFormAction = $this->generateUrl('formManifestation');

		return $this->render('MainBundle:Manifestation:manifestation.html.twig', array(
					'listeManifestations' => $listeManifestations,
					'urlFormAction' => $urlFormAction));
	}

	/**
	 * @Route("/form", name="formManifestation", options={"expose"=true})
	 */
	public function ajouterManifestationAction(Request $oRequest) {
		$manifestation = new Manifestation();
		$form = $this->createForm(new ManifestationType(), $manifestation);

		$urlFormAction = $this->generateUrl('formManifestation');
		// On vérifie qu'elle est de type POST
		if ($oRequest->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			// À partir de maintenant, la variable $manifestation contient les valeurs entrées dans le formulaire par le visiteur
			$form->handleRequest($oRequest);
			$manifestation->setUtilisateurCreateur($this->oUtilisateurConnecte);

			if ($form->isValid()) {
				// On l'enregistre notre objet $manifestation dans la base de données
				$em = $this->getDoctrine()->getManager();
				$em->persist($manifestation);
				$em->flush();
				return new Response('ok');
			}
		}

		return $this->render('MainBundle:Form:formNewManif.html.twig', array(
					'form' => $form->createView(),
					'urlFormAction' => $urlFormAction));
	}

	/**
	 * @Route("/detail/{id}", name="detail-manifestation", options={"expose"=true}, defaults={"id"=0})
	 * @Template()
	 */
	public function detailManifestationAction(Manifestation $oManifestation) {
		return ['manifestation' => $oManifestation];
	}

	/**
	 * @Route("/participer/{id}", name="participer-manifestation", options={"expose"=true})
	 * @Method({"POST"})
	 */
	public function participerManifestationAction(Request $oRequest, Manifestation $oManifestation) {
		if (!is_null($this->oUtilisateurConnecte)) {
			$oParticipation = new Participation();
			$oParticipation
					->setManifestation($oManifestation)
					->setUtilisateur($this->oUtilisateurConnecte);

			$this->oManager->persist($oParticipation);
			$this->oManager->flush();

			$oRequest->getSession()->getFlashBag()->add('notice', 'Votre participation a bien été enregistrée');

			return new JsonResponse([
				'success' => TRUE,
				'location' => $this->generateUrl('detail-manifestation', ['id' => $oManifestation->getId()])
			]);
		} else {
			return new JsonResponse(['success' => FALSE, 'message' => 'Veuillez vous connecter.']);
		}
	}

	/**
	 * @Route("/organiser/{id}", name="organiser-manifestation", options={"expose"=true})
	 * @Method({"POST"})
	 */
	public function organiserManifestationAction(Request $oRequest, Manifestation $oManifestation) {
		if (!is_null($this->oUtilisateurConnecte)) {
			$oOrganisation = new Organisation();
			$oOrganisation
					->setManifestation($oManifestation)
					->setUtilisateur($this->oUtilisateurConnecte);

			$this->oManager->persist($oOrganisation);
			$this->oManager->flush();

			$oRequest->getSession()->getFlashBag()->add('notice', 'Votre participation a bien été enregistrée');

			return new JsonResponse([
				'success' => TRUE,
				'location' => $this->generateUrl('detail-manifestation', ['id' => $oManifestation->getId()])
			]);
		} else {
			return new JsonResponse(['success' => FALSE, 'message' => 'Veuillez vous connecter.']);
		}
	}

}
