<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\MotsCles;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class AddArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'd-flex justify-content-between flex-wrap'
                ]
            ])
            ->add('art_title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Titre'
            ])
            ->add('art_slug', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Slug'
            ])
            ->add('art_description', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description'
            ])
            ->add('art_content', CKEditorType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Contenu de l\'article'
            ])
            ->add('mots_cles', EntityType::class, [
                'class' => MotsCles::class,
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'd-flex justify-content-between'
                ]
            ])
            ->add('art_image', FileType::class, [
                'attr' => [
                    'class' => 'form-control'

                ],
                'required' => false,
                'label' => 'Téléchargez une image',
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg', 'image/jpg', 'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger le bon format',
                        'maxSizeMessage' => 'Vous avez dépassé la taille maximale autorisée'
                    ])
                ]
            ])
//            ->add('art_created_at', DateType::class)
//            ->add('art_updated_at')
//            ->add('user')
//            ->add('mots_cles', TextType::class, [
//                'attr' => [
//                    'class' => 'form-control'
//                ]
//            ])
//            ->add('categories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
