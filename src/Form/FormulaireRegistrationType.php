<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournamentRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Sélectionner une équipe existante
            ->add('team', ChoiceType::class, [
                'choices' => $options['teams'],
                'choice_label' => function ($team) {
                    return $team->getName();
                },
                'placeholder' => 'Choisir une équipe existante',
                'required' => false,
            ])
            // Créer une nouvelle équipe
            ->add('new_team_name', TextType::class, [
                'label' => 'Nom de la nouvelle équipe',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'teams' => [],
        ]);
    }
}
