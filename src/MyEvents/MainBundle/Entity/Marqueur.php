<?php

namespace MyEvents\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Marqueur
 *
 * @ORM\Table(name="marqueur")
 * @ORM\Entity(repositoryClass="MarqueurRepository")
 */
class Marqueur {

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
	 * @var float
	 * @ORM\Column(name="latitude", type="float", precision=10, scale=6, nullable=false)
	 */
	private $latitude;

	/**
	 * @var float
	 * @ORM\Column(name="longitude", type="float", precision=10, scale=6, nullable=false)
	 */
	private $longitude;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="date_debut", type="datetime", nullable=true)
	 */
	private $dateDebut;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="date_fin", type="datetime", nullable=true)
	 */
	private $dateFin;

	/**
	 * @var Organisation
	 * @ORM\OneToMany(targetEntity="Organisation", mappedBy="marqueur")
	 */
	private $organisations;

	/**
	 * @var Manifestation
	 * @ORM\ManyToOne(targetEntity="Manifestation", cascade={"persist"}, inversedBy="marqueurs")
	 * @ORM\JoinColumn(name="manifestation", referencedColumnName="id", nullable=false)
	 */
	private $manifestation;

	/**
	 * @var TypeMarqueur
	 * @ORM\ManyToOne(targetEntity="TypeMarqueur", cascade={"persist"}, inversedBy="marqueurs")
	 * @ORM\JoinColumn(name="type_marqueur", referencedColumnName="id", nullable=false)
	 */
	private $typeMarqueur;

	/**
	 * Constructor
	 */
	public function __construct() {
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
	 * @return Marqueur
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
	 * Set latitude
	 * @param float $latitude
	 * @return Marqueur
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
		return $this;
	}

	/**
	 * Get latitude
	 * @return float
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * Set longitude
	 * @param float $longitude
	 * @return Marqueur
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
		return $this;
	}

	/**
	 * Get longitude
	 * @return float
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * Set dateDebut
	 * @param \DateTime $dateDebut
	 * @return Marqueur
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
	 * @return Marqueur
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
	 * Add organisations
	 * @param Organisation $organisations
	 * @return Marqueur
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
	 * Set manifestation
	 * @param Manifestation $manifestation
	 * @return Marqueur
	 */
	public function setManifestation(Manifestation $manifestation) {
		$this->manifestation = $manifestation;
		$manifestation->addMarqueur($this);
		return $this;
	}

	/**
	 * Get manifestation
	 * @return Manifestation
	 */
	public function getManifestation() {
		return $this->manifestation;
	}

	/**
	 * Set typeMarqueur
	 * @param TypeMarqueur $typeMarqueur
	 * @return Marqueur
	 */
	public function setTypeMarqueur(TypeMarqueur $typeMarqueur) {
		$this->typeMarqueur = $typeMarqueur;
		$typeMarqueur->addMarqueur($this);
		return $this;
	}

	/**
	 * Get typeMarqueur
	 * @return TypeMarqueur
	 */
	public function getTypeMarqueur() {
		return $this->typeMarqueur;
	}

	public function peutEtreEditePar(Utilisateur $oUtilisateur = NULL) {
		return $this->getManifestation()->peutEtreEditePar($oUtilisateur);
	}

}
