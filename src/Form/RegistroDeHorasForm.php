<?php

namespace App\Form;

use App\Entity\RegistroDeHoras;
use App\Entity\Tarea;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroDeHorasForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha', null, [
                'widget' => 'single_text',
            ])
            ->add('horas')
            ->add('comentario')
            ->add('tarea', EntityType::class, [
                'class' => Tarea::class,
                'choice_label' => 'nombre',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nombre',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistroDeHoras::class,
        ]);
    }
}