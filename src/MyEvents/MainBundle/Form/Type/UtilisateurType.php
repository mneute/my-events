<?php

namespace MyEvents\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UtilisateurType extends AbstractType {

	public function buildForm(FormBuilderInterface $oBuilder, array $tOptions) {
		$oBuilder
				->add('username', 'text', array(
					'label' => 'Login',
					'attr' => array('class' => 'test')
				))
				->add('email', 'text', array(
					'label' => 'Email'
				))
				->add('plainPassword', 'password', array(
					'label' => 'Mot de passe'
				))
				->add('repeatPassword', 'password', array(
					'label' => 'Mot de passe',
					'mapped' => false // ce champ n'existe pas sur l'objet, mais on en a besoin dans le formulaire
				))
				->add('prenom', 'text', array(
					'label' => 'Prénom'
				))
				->add('nom', 'text', array(
					'label' => 'Nom'
				))
				->add('adresse', 'text', array(
					'label' => 'Adresse',
					'required' => false
				))
		;
	}

	public function getName() {
		return 'utilisateur';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'MyEvents\MainBundle\Entity\Utilisateur'
		));
	}

}
