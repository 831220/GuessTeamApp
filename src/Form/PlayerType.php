<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Team name:',
                'attr'=>[
                    'placeholder'=>'Write the name of the player!',
                    'autocomplete'=>'off',
                    'class'=>'form-control',
                    'required'=>'true'
                ]
            ])
            ->add('position', ChoiceType::class, [
                'choices' => array_combine(Player::POSITIONS, Player::POSITIONS),
                'label'=>'Position:',
                'attr'=>[
                    'class'=>'form-control',
                    'required'=>'true'
                ]
            ])
            ->add('team', EntityType::class, [
                'label'=>'Team:',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'choice_label' => 'name',
                'class' => Team::class,

            ])
            ->add('save', SubmitType::class, [
                'label'=>'Save',
                'attr'=>[
                    'class'=>'btn btn-outline-success'
                ]
            ])
        ;
        // Si es para edición, modifica el botón de guardar
        if (isset($options['attr']['is_edit']) && $options['attr']['is_edit']) {
            $builder->add('save', SubmitType::class,
                ['label' => 'Update',
                    'attr' => [
                        'href'=>"{{ path('update_player', {'id':playerId}) }}",
                        'class'=>'btn btn-outline-warning m-3 mt-1'
                    ]]);

        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
