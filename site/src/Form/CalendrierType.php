<?php

namespace App\Form;

use App\Entity\Calendrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'label' => 'Nom'
            ])

            ->add('prenom', TextType::class, [
                'label' => 'Prénom'
            ])

            ->add('argentRecolte', MoneyType::class, [
                'label' => 'Argent récolté',
                'currency' => 'EUR'
            ])

            ->add('nombreCalendrier', IntegerType::class, [
                'label' => 'Nombre de calendriers donnés'
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'VALIDER'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}