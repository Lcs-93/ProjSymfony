<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('modele', TextType::class, [
                'label' => 'Modèle',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('immatriculation', TextType::class, [
                'label' => 'Immatriculation',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prixJournalier', NumberType::class, [
                'label' => 'Prix Journalier (€)',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('disponible', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}