<?php

namespace MyEvents\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TypeMarqueur
 *
 * @ORM\Table(name="type_marqueur", uniqueConstraints={@ORM\UniqueConstraint(name="uq_type_marqueur", columns={"libelle"})}))
 * @ORM\Entity(repositoryClass="TypeMarqueurRepository")
 */
class TypeMarqueur {

	/**
	 * @var integer
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 * @ORM\Column(name="libelle", type="string", length=255)
	 */
	private $libelle;

	/**
	 * @var ArrayCollection|Marqueur
	 * @ORM\OneToMany(targetEntity="Marqueur", mappedBy="typeMarqueur")
	 */
	private $marqueurs;

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set libelle
	 * @param string $libelle
	 * @return TypeMarqueur
	 */
	public function setLibelle($libelle) {
		$this->libelle = $libelle;
		return $this;
	}

	/**
	 * Get libelle
	 * @return string
	 */
	public function getLibelle() {
		return $this->libelle;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->marqueurs = new ArrayCollection();
	}

	/**
	 * Add marqueurs
	 * @param Marqueur $marqueurs
	 * @return TypeMarqueur
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

}
