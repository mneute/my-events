<?php

namespace MyEvents\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Amis
 *
 * @ORM\Table(name="amis", uniqueConstraints={@ORM\UniqueConstraint(name="uq_amis_utilisateurs", columns={"utilisateur_emetteur", "utilisateur_destinataire"})})
 * @ORM\Entity(repositoryClass="MyEvents\MainBundle\Entity\AmisRepository")
 */
class Amis {

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var Utilisateur
	 * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="amiDemandes", cascade={"persist"})
	 * @ORM\JoinColumn(name="utilisateur_emetteur", referencedColumnName="id", nullable=false)
	 */
	private $utilisateurEmetteur;

	/**
	 * @var Utilisateur
	 * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="amiRecus", cascade={"persist"})
	 * @ORM\JoinColumn(name="utilisateur_destinataire", referencedColumnName="id", nullable=false)
	 */
	private $utilisateurDestinataire;

	/**
	 * @var boolean
	 * @ORM\Column(name="valide", type="boolean", nullable=false)
	 */
	private $valide;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="date_reponse", type="datetime", nullable=true)
	 */
	private $dateReponse;

	/**
	 *  @var array
	 * 	@ORM\Column(name="historique_conversation", type="json_array", nullable=true)
	 */
	private $historique_conversation;

	public function __construct() {
		$this->setValide(FALSE);
	}

	/**
	 * Get id
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set valide
	 * @param boolean $valide
	 * @return Amis
	 */
	public function setValide($valide) {
		$this->valide = $valide;
		return $this;
	}

	/**
	 * Get valide
	 * @return boolean
	 */
	public function getValide() {
		return $this->valide;
	}

	/**
	 * Set utilisateurEmetteur
	 * @param \MyEvents\MainBundle\Entity\Utilisateur $utilisateurEmetteur
	 * @return Amis
	 */
	public function setUtilisateurEmetteur(\MyEvents\MainBundle\Entity\Utilisateur $utilisateurEmetteur = null) {
		$this->utilisateurEmetteur = $utilisateurEmetteur;
		$utilisateurEmetteur->addAmiDemande($this);
		return $this;
	}

	/**
	 * Get utilisateurEmetteur
	 * @return \MyEvents\MainBundle\Entity\Utilisateur
	 */
	public function getUtilisateurEmetteur() {
		return $this->utilisateurEmetteur;
	}

	/**
	 * Set utilisateurDestinataire
	 * @param \MyEvents\MainBundle\Entity\Utilisateur $utilisateurDestinataire
	 * @return Amis
	 */
	public function setUtilisateurDestinataire(\MyEvents\MainBundle\Entity\Utilisateur $utilisateurDestinataire = null) {
		$this->utilisateurDestinataire = $utilisateurDestinataire;
		$utilisateurDestinataire->addAmiRecu($this);
		return $this;
	}

	/**
	 * Get utilisateurDestinataire
	 * @return \MyEvents\MainBundle\Entity\Utilisateur
	 */
	public function getUtilisateurDestinataire() {
		return $this->utilisateurDestinataire;
	}

	/**
	 * Set dateReponse
	 * @param \DateTime $dateReponse
	 * @return Amis
	 */
	public function setDateReponse($dateReponse) {
		$this->dateReponse = $dateReponse;
		return $this;
	}

	/**
	 * Get dateReponse
	 * @return \DateTime
	 */
	public function getDateReponse() {
		return $this->dateReponse;
	}

	/**
	 * Set historique_conversation
	 *
	 * @param array $historiqueConversation
	 * @return Amis
	 */
	public function setHistoriqueConversation($historiqueConversation) {
		$this->historique_conversation = $historiqueConversation;

		return $this;
	}

	/**
	 * Get historique_conversation
	 *
	 * @return array
	 */
	public function getHistoriqueConversation() {
		return $this->historique_conversation;
	}

}
