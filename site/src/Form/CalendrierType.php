<?php

namespace App\Form;

use App\Entity\Calendrier;
use App\Entity\Tournee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('prenomPompier', TextType::class, [
                'label' => 'Prénom du pompier',
            ])
            ->add('tournee', EntityType::class, [
                'class' => Tournee::class,
                'choice_label' => function (Tournee $tournee) {
                    return $tournee->getVille() . ' (' . $tournee->getCodePostal() . ')';
                },
                'label' => 'Ville de la tournée',
                'placeholder' => 'Choisir une ville',
                'required' => true,
            ])
            ->add('argentRecolte', MoneyType::class, [
                'label' => 'Argent récolté',
                'currency' => 'EUR',
            ])
            ->add('nombreCalendrier', IntegerType::class, [
                'label' => 'Nombre de calendriers vendus',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'VALIDER',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}