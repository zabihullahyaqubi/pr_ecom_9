<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Tva;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('marque')
            ->add('taille')
            ->add('image', FileType::class,['label'=>'Téléchargez une image' , 
                 'data_class'=> null,
                 'required'=>false,
                 'empty_data'=>''])
            ->add('prix')
            ->add('description')
            ->add('couleur')
            ->add('ref')
            ->add('quantity')
            ->add('category')
            ->add('tva', EntityType::class ,[
                'class'=> Tva::class,
                'choice_label'=>'taux_tva'
            ])
            

            ->add('submit' , SubmitType::class,['label'=>'Ajouter article',
            'attr'=>['class'=>'btn btn-primary mt-2']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
