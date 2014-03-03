<?php

namespace MyEvents\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organisation
 *
 * @ORM\Table(name="organisation", uniqueConstraints={@ORM\UniqueConstraint(name="uq_organisation_utilisateur_manifestation", columns={"utilisateur", "manifestation"})}))
 * @ORM\Entity(repositoryClass="OrganisationRepository")
 */
class Organisation {

	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var Utilisateur
	 * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist"}, inversedBy="organisations")
	 * @ORM\JoinColumn(name="utilisateur", referencedColumnName="id", nullable=false)
	 */
	private $utilisateur;

	/**
	 * @var Manifestation
	 * @ORM\ManyToOne(targetEntity="Manifestation", cascade={"persist"}, inversedBy="organisations")
	 * @ORM\JoinColumn(name="manifestation", referencedColumnName="id", nullable=false)
	 */
	private $manifestation;

	/**
	 * @var Marqueur
	 * @ORM\ManyToOne(targetEntity="Marqueur", cascade={"persist"}, inversedBy="organisations")
	 * @ORM\JoinColumn(name="marqueur", referencedColumnName="id")
	 */
	private $marqueur;

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set utilisateur
	 * @param Utilisateur $utilisateur
	 * @return Organisation
	 */
	public function setUtilisateur(Utilisateur $utilisateur) {
		$this->utilisateur = $utilisateur;
		$utilisateur->addOrganisation($this);
		return $this;
	}

	/**
	 * Get utilisateur
	 * @return Utilisateur
	 */
	public function getUtilisateur() {
		return $this->utilisateur;
	}

	/**
	 * Set manifestation
	 * @param Manifestation $manifestation
	 * @return Organisation
	 */
	public function setManifestation(Manifestation $manifestation) {
		$this->manifestation = $manifestation;
		$manifestation->addOrganisation($this);
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
	 * Set marqueur
	 * @param Marqueur $marqueur
	 * @return Organisation
	 */
	public function setMarqueur(Marqueur $marqueur = null) {
		$this->marqueur = $marqueur;
		$marqueur->addOrganisation($this);
		return $this;
	}

	/**
	 * Get marqueur
	 * @return Marqueur
	 */
	public function getMarqueur() {
		return $this->marqueur;
	}

}
