<?php

namespace Images\ImagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('file', 'file', array('required' => true, 'label' => 'Choisir un fichier'))
            ->add('titre', null, array('pattern' => '[A-Za-z0-9-]{2,}'))
            ->add('affiche', null, array('pattern' => '[0-9A-Za-zÀÂÄÉÈÊËÎÏÔÔÙÛÜÇàâäéèêëîïôöùûüç .!?\'-]{2,}'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Images\ImagesBundle\Entity\Image'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'images_imagesbundle_image';
    }
}
