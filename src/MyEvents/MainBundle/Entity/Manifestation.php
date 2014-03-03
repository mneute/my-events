<?php

namespace MyEvents\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Manifestation
 *
 * @ORM\Table(name="manifestation")
 * @ORM\Entity(repositoryClass="ManifestationRepository")
 */
class Manifestation {

	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 * @ORM\Column(name="nom", type="string", length=255, nullable=false)
	 */
	private $nom;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="date_debut", type="datetime", nullable=false)
	 */
	private $dateDebut;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="date_fin", type="datetime", nullable=false)
	 */
	private $dateFin;

	/**
	 * @var string
	 * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
	 */
	private $adresse;

	/**
	 * @var Utilisateur
	 * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist"}, inversedBy="manifestations")
	 * @ORM\JoinColumn(name="utilisateur_createur", referencedColumnName="id", nullable=false)
	 */
	private $utilisateurCreateur;

	/**
	 * @var Organisation
	 * @ORM\OneToMany(targetEntity="Organisation", mappedBy="manifestation")
	 */
	private $organisations;

	/**
	 * @var Participation
	 * @ORM\OneToMany(targetEntity="Participation", mappedBy="manifestation")
	 */
	private $participations;

	/**
	 * @var Marqueur
	 * @ORM\OneToMany(targetEntity="Marqueur", mappedBy="manifestation")
	 */
	private $marqueurs;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->participations = new ArrayCollection();
		$this->organisations = new ArrayCollection();
	}

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set nom
	 * @param string $nom
	 * @return Manifestation
	 */
	public function setNom($nom) {
		$this->nom = $nom;
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
	 * Set dateDebut
	 * @param \DateTime $dateDebut
	 * @return Manifestation
	 */
	public function setDateDebut($dateDebut) {
		$this->dateDebut = $dateDebut;
		return $this;
	}

	/**
	 * Get dateDebut
	 * @return \DateTime
	 */
	public function getDateDebut() {
		return $this->dateDebut;
	}

	/**
	 * Set dateFin
	 * @param \DateTime $dateFin
	 * @return Manifestation
	 */
	public function setDateFin($dateFin) {
		$this->dateFin = $dateFin;
		return $this;
	}

	/**
	 * Get dateFin
	 * @return \DateTime
	 */
	public function getDateFin() {
		return $this->dateFin;
	}

	/**
	 * Set adresse
	 * @param string $adresse
	 * @return Manifestation
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
	 * Set utilisateurCreateur
	 * @param Utilisateur $utilisateurCreateur
	 * @return Manifestation
	 */
	public function setUtilisateurCreateur(Utilisateur $utilisateurCreateur = null) {
		$this->utilisateurCreateur = $utilisateurCreateur;
		$utilisateurCreateur->addManifestation($this);
		return $this;
	}

	/**
	 * Get utilisateurCreateur
	 * @return Utilisateur
	 */
	public function getUtilisateurCreateur() {
		return $this->utilisateurCreateur;
	}

	/**
	 * Add organisations
	 * @param Organisation $organisations
	 * @return Manifestation
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
	 * @return Manifestation
	 */
	public function addParticipation(Participation $participations) {
		$this->participations[] = $participations;

		return $this;
	}

	/**
	 * Remove participations
	 * @param Participation $participations
	 */
	public function removeParticipation(Participation $participations) {
		$this->participations->removeElement($participations);
	}

	/**
	 * Get participations
	 * @return ArrayCollection
	 */
	public function getParticipations() {
		return $this->participations;
	}

	/**
	 * Add marqueurs
	 * @param Marqueur $marqueurs
	 * @return Manifestation
	 */
	public function addMarqueur(Marqueur $marqueurs) {
		$this->marqueurs[] = $marqueurs;
		return $this;
	}

	/**
	 * Remove marqueurs
	 * @param Marqueur $marqueurs
	 */
	public function removeMarqueur(Marqueur $marqueurs) {
		$this->marqueurs->removeElement($marqueurs);
	}

	/**
	 * Get marqueurs
	 * @return ArrayCollection
	 */
	public function getMarqueurs() {
		return $this->marqueurs;
	}

	public function utilisateurEstParticipant(Utilisateur $oUtilisateur) {
		return $this->getParticipations()->exists(function($key, Participation $oParticipation) use ($oUtilisateur) {
					return ($oParticipation->getUtilisateur() == $oUtilisateur);
				});
	}

	public function utilisateurEstOrganisateur(Utilisateur $oUtilisateur) {
		return $this->getOrganisations()->exists(function($key, Organisation $oOrganisation) use ($oUtilisateur) {
					return ($oOrganisation->getUtilisateur() == $oUtilisateur);
				});
	}

	public function utilisateurEstCreateur(Utilisateur $oUtilisateur) {
		return ($this->getUtilisateurCreateur() == $oUtilisateur);
	}

	public function peutEtreEditePar(Utilisateur $oUtilisateur = NULL) {
		if (is_null($oUtilisateur)) {
			return FALSE;
		} else {
			return ($this->utilisateurEstOrganisateur($oUtilisateur) ||
					$this->utilisateurEstCreateur($oUtilisateur) ||
					$oUtilisateur->estAdministrateur());
		}
	}

}
