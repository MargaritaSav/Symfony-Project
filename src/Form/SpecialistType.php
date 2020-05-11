<?php

namespace App\Form;

use App\Entity\Specialist;
use App\Entity\Schedule;
use App\Repository\SpecialistRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SpecialistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('specialist', EntityType::class,[
                'class' => Specialist::class,
                'choice_value' => function(?Specialist $specialist){
                        return $specialist ? $specialist->getId() : '';
                    },
                'query_builder' => function (SpecialistRepository $sr) {
                        return $sr->createQueryBuilder('s')
                            ->join('s.appointments', 'appointments');
                            
                            
                    },
                'placeholder' => 'Расписание всех специалистов', 
                'label' => 'Выберите специалиста',
                'required'=>false
            ]);

            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
            
        ]);
        
    }
}
