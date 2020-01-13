<?php

namespace App\Form;

use App\Entity\Comments; /*il emporte l'entité Comments*/
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface; /* il utilise l'interface de création de formulaire */
use Symfony\Component\OptionsResolver\OptionsResolver; /* un composant symfony qui permet de résoudre les options qu'on va pouvoir passer au niveau du formulaire*/

class CommentsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email_author', EmailType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('com_content')

            //            ->add('pseudo')
            ->add('rgpd', CheckboxType::class, [
                'label' => 'J\'accepte la collecte de mes données'
            ])
            ->add('Envoyer', SubmitType::class)
//            ->add('actif')
//            ->add('com_created_at')
//            ->add('article')
//            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
