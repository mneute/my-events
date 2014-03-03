<?php

namespace MyEvents\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="UtilisateurRepository")
 */
class Utilisateur extends User {

	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
	 */
	private $prenom;

	/**
	 * @var string
	 * @ORM\Column(name="nom", type="string", length=255, nullable=false)
	 */
	private $nom;

	/**
	 * @var string
	 * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
	 */
	private $adresse;

	/**
	 * @var Manifestation
	 * @ORM\OneToMany(targetEntity="Manifestation", mappedBy="utilisateurCreateur")
	 */
	private $manifestations;

	/**
	 * @var Amis
	 * @ORM\OneToMany(targetEntity="Amis", mappedBy="utilisateurEmetteur")
	 */
	private $amiDemandes;

	/**
	 * @var Amis
	 * @ORM\OneToMany(targetEntity="Amis", mappedBy="utilisateurDestinataire")
	 */
	private $amiRecus;

	/**
	 * @var Organisation
	 * @ORM\OneToMany(targetEntity="Organisation", mappedBy="utilisateur")
	 */
	private $organisations;

	/**
	 * @var Participation
	 * @ORM\OneToMany(targetEntity="Participation", mappedBy="utilisateur")
	 */
	private $participations;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->manifestations = new ArrayCollection();
		$this->amiDemandes = new ArrayCollection();
		$this->amiRecus = new ArrayCollection();
		$this->organisations = new ArrayCollection();
		$this->participations = new ArrayCollection();
	}

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set prenom
	 * @param string $prenom
	 * @return Utilisateur
	 */
	public function setPrenom($prenom) {
		$this->prenom = ucfirst(strtolower($prenom));
		return $this;
	}

	/**
	 * Get prenom
	 * @return string
	 */
	public function getPrenom() {
		return $this->prenom;
	}

	/**
	 * Set nom
	 * @param string $nom
	 * @return Utilisateur
	 */
	public function setNom($nom) {
		$this->nom = strtoupper($nom);
		return $this;
	}

	/**
	 * Get nom
	 * @return string
	 */
	public function getNom() {
		return $this->nom;
	}

	/**
	 * Set adresse
	 * @param string $adresse
	 * @return Utilisateur
	 */
	public function setAdresse($adresse) {
		$this->adresse = $adresse;
		return $this;
	}

	/**
	 * Get adresse
	 * @return string
	 */
	public function getAdresse() {
		return $this->adresse;
	}

	/**
	 * Add manifestations
	 * @param Manifestation $manifestations
	 * @return Utilisateur
	 */
	public function addManifestation(Manifestation $manifestations) {
		$this->manifestations[] = $manifestations;
		return $this;
	}

	/**
	 * Remove manifestations
	 * @param Manifestation $manifestations
	 */
	public function removeManifestation(Manifestation $manifestations) {
		$this->manifestations->removeElement($manifestations);
	}

	/**
	 * Get manifestations
	 * @return ArrayCollection
	 */
	public function getManifestations() {
		return $this->manifestations;
	}

	/**
	 * Add organisations
	 * @param Organisation $organisations
	 * @return Utilisateur
	 */
	public function addOrganisation(Organisation $organisations) {
		$this->organisations[] = $organisations;
		return $this;
	}

	/**
	 * Remove organisations
	 * @param Organisation $organisations
	 */
	public function removeOrganisation(Organisation $organisations) {
		$this->organisations->removeElement($organisations);
	}

	/**
	 * Get organisations
	 * @return ArrayCollection
	 */
	public function getOrganisations() {
		return $this->organisations;
	}

	/**
	 * Add participations
	 * @param Participation $participations
	 * @return Utilisateur
	 */
	public function addParticipation(Participation $participations) {
		$this->participations[] = $participations;
		return $this;
	}

	/**
	 * Remove participations
	 *
	 * @param Participation $participations
	 */
	public function removeParticipation(Participation $participations) {
		$this->participations->removeElement($participations);
	}

	/**
	 * Get participations
	 *
	 * @return ArrayCollection
	 */
	public function getParticipations() {
		return $this->participations;
	}

	/**
	 * Add amiDemandes
	 * @param Amis $amiDemandes
	 * @return Utilisateur
	 */
	public function addAmiDemande(Amis $amiDemandes) {
		$this->amiDemandes[] = $amiDemandes;
		return $this;
	}

	/**
	 * Remove amiDemandes
	 * @param Amis $amiDemandes
	 */
	public function removeAmiDemande(Amis $amiDemandes) {
		$this->amiDemandes->removeElement($amiDemandes);
	}

	/**
	 * Get amiDemandes
	 * @return ArrayCollection
	 */
	public function getAmiDemandes() {
		return $this->amiDemandes;
	}

	/**
	 * Add amiRecus
	 * @param Amis $amiRecus
	 * @return Utilisateur
	 */
	public function addAmiRecu(Amis $amiRecus) {
		$this->amiRecus[] = $amiRecus;
		return $this;
	}

	/**
	 * Remove amiRecus
	 * @param Amis $amiRecus
	 */
	public function removeAmiRecu(Amis $amiRecus) {
		$this->amiRecus->removeElement($amiRecus);
	}

	/**
	 * Get amiRecus
	 *
	 * @return ArrayCollection
	 */
	public function getAmiRecus() {
		return $this->amiRecus;
	}

	/**
	 * Retourne un tableau de tous les amis (demandés et reçus) qui sont validés
	 * @return ArrayCollection|Utilisateur
	 */
	public function getListeAmisValides() {
		$toAmis = new ArrayCollection();
		foreach ($this->getAmiDemandes() as $oAmi) {
			/* @var $oAmi Amis */
			if ($oAmi->getValide()) {
				$toAmis->add($oAmi->getUtilisateurDestinataire());
			}
		}

		foreach ($this->getAmiRecus() as $oAmi) {
			if ($oAmi->getValide()) {
				$toAmis->add($oAmi->getUtilisateurEmetteur());
			}
		}
		return $toAmis;
	}

	public function estAmiAvec(Utilisateur $oUtilisateur) {
		return $this->getListeAmisValides()->contains($oUtilisateur);
	}

	/**
	 * Retourne la liste des amis en attente de réponse (demande envoyées ou reçues)
	 * @return ArrayCollection|Utilisateur
	 */
	public function getListeAmisEnAttente() {
		$toAmis = new ArrayCollection();
		foreach ($this->getAmiDemandes() as $oAmi) {
			/* @var $oAmi Amis */
			if (is_null($oAmi->getDateReponse())) {
				$toAmis->add($oAmi->getUtilisateurDestinataire());
			}
		}

		foreach ($this->getAmiRecus() as $oAmi) {
			if (is_null($oAmi->getDateReponse())) {
				$toAmis->add($oAmi->getUtilisateurEmetteur());
			}
		}

		return $toAmis;
	}

	public function estAmiEnAttenteAvec(Utilisateur $oUtilisateur) {
		return $this->getListeAmisEnAttente()->contains($oUtilisateur);
	}

	public function getListesDemandeAmiEnAttente(Utilisateur $oUtilisateur) {
		$toAmis = new ArrayCollection();

		foreach ($this->getAmiRecus() as $oAmi) {
			/* @var $oAmi Amis */
			if ((is_null($oAmi->getDateReponse()) && $oAmi->getUtilisateurDestinataire() == $oUtilisateur)) {
				$toAmis->set($oAmi->getId(), $oAmi->getUtilisateurEmetteur());
			}
		}

		return $toAmis;
	}

	public function getListesAmi(Utilisateur $oUtilisateur) {
		$toAmis = new ArrayCollection();

		foreach ($this->getAmiRecus() as $oAmi) {
			/* @var $oAmi Amis */
			if ((!is_null($oAmi->getDateReponse()) && $oAmi->getUtilisateurDestinataire() == $oUtilisateur)) {
				$toAmis->add($oAmi->getUtilisateurEmetteur());
			}
		}

		return $toAmis;
	}

	public function estAdministrateur() {
		return in_array('ROLE_ADMIN', $this->getRoles());
	}
}
