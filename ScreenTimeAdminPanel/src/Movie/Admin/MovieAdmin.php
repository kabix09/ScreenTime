<?php

declare(strict_types=1);

namespace App\Movie\Admin;

use App\Movie\Entity\Movie;
use App\Movie\Form\MovieCharacterType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Sonata\DoctrineORMAdminBundle\Filter\NumberFilter;
use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;

final class MovieAdmin extends AbstractAdmin
{
    private const ADMIN_ID = 'movie';
    private const OLDEST_DATE = 1900;

    protected $baseRoutePattern;

    public function __construct(?string $code = null, ?string $class = null, ?string $baseControllerName = null)
    {
        $this->baseRoutePattern = self::ADMIN_ID;

        parent::__construct($code, $class, $baseControllerName);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('title', TextType::class)
            ->add('productionYear', ChoiceType::class, [
                'choices' => $this->generateProductionYearsOptions(self::OLDEST_DATE),
                'setter' => function (Movie &$movie, int $year, FormInterface $form): void {
                    $movie->setProductionYear((new \DateTime(sprintf('%s-01-01', $year))));
                },
            ])
            ->add('durationTime', IntegerType::class)  // conflict int type vs time type in data base
            ->add('worldPremiereDate', DateType::class, [
                'years' => range(self::OLDEST_DATE, date('Y')),
            ])
//            ->add('movieCharacters', CollectionType::class, [
//                'label' => 'Characters',
//                'choice_label' => 'character',
//                'choice_value' => 'movie',
//                'class' => TextType::class,
//                /*'type_options' => [
//                    // Prevents the "Delete" option from being displayed
//                    'delete' => true,
//                    'delete_options' => [
//                        // You may otherwise choose to put the field but hide it
//                        'type'         => MovieCharacter::class,
//                        // In that case, you need to fill in the options as well
//                        'type_options' => [
//                            'mapped'   => true,
//                            'required' => false,
//                        ]
//                    ]
//                ]*/
//            ])
//
            ->add('movieCharacters', CollectionType::class, [
                'label' => 'Characters',
                'required' => false,
                'by_reference' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'entry_type' => MovieCharacterType::class,
            ])
        ;

        //$form->getFormBuilder()->setDataMapper(new IntToYearMapper());
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('name', ChoiceFilter::class)
            ->add('productionYear', ChoiceFilter::class)
            ->add('worldPremiereDate', DateRangeFilter::class)
            ->add('durationTime', NumberFilter::class)
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('title')
            ->add('productionYear', FieldDescriptionInterface::TYPE_STRING, [
                'accessor' => function(Movie $movie) {
                    return $movie->getProductionYear()->format('Y');
                }
            ])
            ->add('durationTime', FieldDescriptionInterface::TYPE_STRING, [
                'accessor' => function(Movie $movie) {
                    return sprintf('%s mm', $movie->getDurationTime());
                }
            ])
            ->add('worldPremiereDate', FieldDescriptionInterface::TYPE_DATE, [
                'accessor' => function(Movie $movie) {
                    return $movie->getWorldPremiereDate()->format('Y-m-d');
                }
            ])
            ->add(ListMapper::NAME_ACTIONS, ListMapper::TYPE_ACTIONS, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    // 'template' => 'Admin/MyController/my_partial.html.twig'
                    //this twig file will be located at: templates/Admin/MyController/my_partial.html.twig
                ]
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        // https://stackoverflow.com/questions/28361347/sonata-admin-bundle-preview-image-from-some-entity-in-list-mapper-without-sonata/28381786#28381786
//        ->with('Characters', [
//        'class'       => 'col-md-8',
//        'box_class'   => 'box box-solid box-danger',
//        'description' => 'Lorem ipsum'
//        ])
//            ->add('movieCharacters', fieldDescriptionOptions: [
//                'template' => 'admin/movie/field/show_characters_collection.html.twig',
//            ])
//        ->end();

        $show
            ->with('Picture', [
                'class' => 'col-md-4'
            ])
//                ->add('moviePicture', fieldDescriptionOptions: [
//                    'template' => 'admin/field/show_picture.html.twig',
//                ])
            ->end()
            ->with('Movie', [
                'class' => 'col-md-8'
            ])
                ->add('title')
                ->add('productionYear', fieldDescriptionOptions: [
                    'format' => 'Y'
                ])
                ->add('durationTime', fieldDescriptionOptions: [
                    'accessor' => function(Movie $movie) {
                        return sprintf("%s min", $movie->getDurationTime());
                    }
                ])
                ->add('worldPremiereDate', fieldDescriptionOptions: [
                    'format' => 'Y-m-d'
                ])
            ->end()
            ->with('Characters')
                ->add('movieCharacters', fieldDescriptionOptions: [
                    'template' => 'admin/movie/field/show_characters_collection.html.twig',
                ])
            ->end()

        ;
    }

    /*
     * OVERWRITE EVENTS FUNCTIONS
     */

    /**
     * @param Movie $object
     * @return void
     */
    public function prePersist($object): void
    {
        foreach ($object->getMovieCharacters() as &$movieCharacter) {
            $movieCharacter->setMovie($object);
        }
    }

    /**
     * @param Movie $object
     * @return void
     */
    public function preUpdate($object): void
    {
        foreach ($object->getMovieCharacters() as &$movieCharacter) {
            $movieCharacter->setMovie($object);
        }
    }

    /*
     * SUPPORT FUNCTION
     */

    private function generateProductionYearsOptions(int $oldestDate)
    {
        return array_combine(
            range($oldestDate, date('Y')),
            range($oldestDate, date('Y'))
        );
    }
}