<?php

namespace App\Form;

use App\Entity\Hobby;
use App\Entity\InfosUser;
use App\Entity\Job;
use App\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class InfosUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => ['min' => 1, 'max' => 200],
            ])
            ->add('createdAt')
            ->add('updatedAt')
            ->add('profile', EntityType::class, [
                'label' => 'Sélectionnez votre profile',
                'expanded' => false,
                'multiple' => false,
                'class' => Profile::class,
                'attr' => ['class' => 'select2'],
            ])
            ->add('job', EntityType::class, [
                'label' => 'Sélectionnez votre job',
                'expanded' => false,
                'multiple' => false,
                'class' => Job::class,
                'attr' => ['class' => 'select2'],
            ])
            ->add('hobbies', EntityType::class, [
                'label' => 'Choisissez vos hobbies',
                'expanded' => false,
                'multiple' => true,
                'class' => Hobby::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('h')->orderBy('h.designation', 'ASC');
                },
                'attr' => ['class' => 'select2'],
                //'choice_label' => 'designation',
                'choice_label' => function($hobbies){
                    return $hobbies->getDesignation();
                }
            ])
            ->add('photo', FileType::class, [
                'label' => 'Ajoutez une photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (Gif, Jpeg, Jpg)',
                    ])
                ],
            ])
            ->add('Editer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfosUser::class,
        ]);
    }
}
