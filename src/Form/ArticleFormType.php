<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom',])
            ->add('description')
            ->add('price',TextType::class, ['label' => 'Prix',])
            ->add('stock')
            ->add('tva')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie(s)',
                'multiple'=>true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            /*->add('categories', CollectionType::class, [
                'label' => 'Catégorie',
                // Ici on voulait ajouter plusieurs entity, donc avoir une Collection d'EntityType
                'entry_type' => EntityType::class,
                // Il faut bien penser à ajouter les allow_add et allow_delete afin de pouvoir ajouter les formulaires au fur et à mesure
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    // Pensez à bien garder ce nom de data-selector si vous utilisez mon JS
                    // La valeur du data selector doit être identique à celle du bouton d'ajout
                    'data-list-selector' => 'categories'
                ],
                // On rempli les informations nécessaires au paramétrage de l'EntityType dans "entry_options"
                'entry_options' => [
                    'label' => false,
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    },
                ]
            ])
            // Le code du bouton permettant d'ajouter un formulaire de "country" (ici on utilise un EntityType)
            ->add('addCategory', ButtonType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn btn-info',
                    // Même punition que pour le "data-list-selector" définit dans le CollectionType
                    // On ne change pas son nom (si vous gardez mon JS) et sa valeur doit être identique à celle la CollectionType
                    'data-btn-selector' => 'categories',
                ]
            ])*/
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'mapped' => false,
                    'class' => 'btn-success',
                ],
                'row_attr' => [
                    'class' => 'mb-3 text-center'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
