<?php

namespace MyEvents\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="participation", uniqueConstraints={@ORM\UniqueConstraint(name="uq_participation_utilisateur_manifestation", columns={"utilisateur", "manifestation"})})))
 * @ORM\Entity(repositoryClass="ParticipationRepository")
 */
class Participation {

	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var Utilisateur
	 * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="participations", cascade={"persist"})
	 * @ORM\JoinColumn(name="utilisateur", referencedColumnName="id", nullable=false)
	 */
	private $utilisateur;

	/**
	 * @var Manifestation
	 * @ORM\ManyToOne(targetEntity="Manifestation", inversedBy="participations", cascade={"persist"})
	 * @ORM\JoinColumn(name="manifestation", referencedColumnName="id", nullable=false)
	 */
	private $manifestation;

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
	 * @return Participation
	 */
	public function setUtilisateur(Utilisateur $utilisateur) {
		$this->utilisateur = $utilisateur;
		$utilisateur->addParticipation($this);
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
	 * @return Participation
	 */
	public function setManifestation(Manifestation $manifestation) {
		$this->manifestation = $manifestation;
		$manifestation->addParticipation($this);
		return $this;
	}

	/**
	 * Get manifestation
	 * @return Manifestation
	 */
	public function getManifestation() {
		return $this->manifestation;
	}

}
