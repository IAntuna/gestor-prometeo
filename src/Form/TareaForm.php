<?php

namespace App\Form;

use App\Entity\Proyecto;
use App\Entity\Tarea;
use App\Entity\Tipologia;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TareaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('estado')
            ->add('plazo', null, [
                'widget' => 'single_text',
            ])
            ->add('horasEstimadas')
            ->add('horasRealizadas')
            ->add('proyecto', EntityType::class, [
                'class' => Proyecto::class,
                'choice_label' => 'nombre',
            ])
            ->add('tipologia', EntityType::class, [
                'class' => Tipologia::class,
                'choice_label' => 'nombre',
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nombre',
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tarea::class,
        ]);
    }
}