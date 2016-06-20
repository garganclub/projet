<?php
namespace Utilisateurs\UtilisateursBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CountryType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('pays', 'country')
			;
	}
	public function getName() {
		return 'utilisateurs_utilisateursbundle_country';
	}
}