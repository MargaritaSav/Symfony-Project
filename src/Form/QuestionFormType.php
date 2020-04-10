<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('askedBy_name', TextType::class, [
            'attr' => ['placeholder' => 'Имя и Фамилия'], 'label' => false])
            ->add('askedBy_email', EmailType::class, [
            'attr' => ['placeholder' => 'e-mail'], 'label' => false])
            ->add('question', TextareaType::class, [
            'data' => 'Ваш вопрос', 'label' => false])
            ->add('send', SubmitType::class, ['label' => 'Отправить вопрос', 'attr' => ['class' => 'button']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
