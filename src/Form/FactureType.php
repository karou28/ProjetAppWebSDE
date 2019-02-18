<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mois')
            ->add('consommation')
            ->add('prix')
            ->add('reglement')
            ->add('abonnement', EntityType::class, array(
                'class' => 'App\Entity\Abonnement',
                'choice_label' => 'contrat',
                'expanded' => false,
                'multiple' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
