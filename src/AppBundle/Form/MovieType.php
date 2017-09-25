<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imdbId')
            ->add('title')
            ->add('year')
            ->add('cast')
            ->add('directors')
            ->add('writers')
            ->add('plot')
            ->add('rating')
            ->add('votes')
            ->add('runtime')
            ->add('trailerId')
            ->add('dateCreated')
            ->add('dateModified')
            ->add('genres')
            ->add('submit', SubmitType::class, ["label" => "Valider"])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Movie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_movie';
    }


}
