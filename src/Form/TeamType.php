<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Team name:',
                'attr'=>[
                    'placeholder'=>'Write the name of the team!',
                    'autocomplete'=>'off',
                    'class'=>'form-control',
                    'required'=>'true'
                ]
            ])
            ->add('color', ColorType::class, [ // This field is not used in game. However, its required
                'label' => 'Hex color:',
                'attr' => [
                    'class' => 'form-control'
                ]
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
                'href'=>"{{ path('update_team', {'id':teamId}) }}",
                'class'=>'btn btn-outline-warning m-3 mt-1'
                ]]);

        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
