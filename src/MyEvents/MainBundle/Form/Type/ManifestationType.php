<?php

namespace MyEvents\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ManifestationType extends AbstractType {

	public function buildForm(FormBuilderInterface $oBuilder, array $tOptions) {
		$oBuilder
				->add('nom', 'text', array(
					'label' => 'Nom',
					'required' => true
				))
				->add('dateDebut', 'date', array(
					'label' => 'Date de début',
					'widget' => 'single_text',
					'input' => 'datetime',
					'format' => 'dd/MM/yyyy',
					'attr' => array('class' => 'datepiker', 'size' => 10),
					'required' => true
				))
				->add('dateFin', 'date', array(
					'label' => 'Date de fin',
					'required' => true,
					'widget' => 'single_text',
					'input' => 'datetime',
					'format' => 'dd/MM/yyyy',
					'attr' => array('class' => 'datepicker', 'size' => 10)
				))
				->add('adresse', 'text', array(
					'label' => 'Adresse',
					'required' => true
				))
				->add('utilisateurCreateur', 'hidden', array())
		;
	}

	public function getName() {
		return 'manifestation';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults([
			'data_class' => 'MyEvents\MainBundle\Entity\Manifestation'
		]);
	}
}
