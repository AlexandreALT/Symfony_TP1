<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class NewMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'required' => true,
                'label' => "Entrez votre message :",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un message'
                    ]),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Le message doit contenir maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => "Ajouter"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
