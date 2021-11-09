<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Measurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('temperature',NumberType::class)
            ->add('humidity',NumberType::class)
            ->add('wind_strength',NumberType::class)
            ->add('description',TextType::class)
            ->add('date',DateType::class)
//            ->add('cityName',TextType::class,array('mapped' => false))
                ->add('city_id',EntityType::class,[
                    'class'=>City::class,
                    'choice_label'=>'name',
            ])
            ->add('save',SubmitType::class,['label'=>'Create Measurement']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
