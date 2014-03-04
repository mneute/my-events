<?php

namespace MyEvents\MainBundle\Form\Type;

use MyEvents\MainBundle\Entity\TypeMarqueurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MarqueurType extends AbstractType {

	public function buildForm(FormBuilderInterface $oBuilder, array $tOptions) {
		$cQueryBuilder = function(TypeMarqueurRepository $oMarqueurRepo) {
			return $oMarqueurRepo->createQueryBuilder('m');
		};

		$formatDate = 'dd/MM/yyyy HH:mm:ss';

		$oBuilder
				->add('nom', 'text', ['label' => 'Nom'])
				->add('typeMarqueur', 'entity', [
					'label' => 'Type',
					'class' => 'MainBundle:TypeMarqueur',
					'property' => 'libelle',
					'query_builder' => $cQueryBuilder,
				])
				->add('dateDebut', 'datetime', [
					'label' => 'Date de début',
					'required' => true,
					'widget' => 'single_text',
					'input' => 'datetime',
					'format' => $formatDate,
					'attr' => ['class' => 'datepicker', 'size' => 10]
				])
				->add('dateFin', 'date', [
					'label' => 'Date de fin',
					'required' => true,
					'widget' => 'single_text',
					'input' => 'datetime',
					'format' => $formatDate,
					'attr' => ['class' => 'datepicker', 'size' => 10]
				])
		;
	}

	public function getName() {
		return 'marqueur';
	}

	public function setDefaultOptions(OptionsResolverInterface $oResolver) {
		$oResolver->setDefaults([
			'data_class' => 'MyEvents\MainBundle\Entity\Marqueur'
		]);
	}
}
