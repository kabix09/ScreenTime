<?php

declare(strict_types=1);

namespace App\Actor\Admin;

use App\Actor\Entity\Actor;
use App\Character\Entity\Character;
use App\Country\Entity\Country;
use App\Movie\Entity\Movie;
use Carbon\Carbon;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class ActorAdmin extends AbstractAdmin
{
    private const ADMIN_ID = 'actor';
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
                ->add('name', TextType::class)
                ->add('surname', TextType::class)
                ->add('birthDate', DateType::class, [
                    'years' => range(self::OLDEST_DATE, date('Y')),
                ])
                ->add('nationality', EntityType::class, [
                    'class' => Country::class,
                    'choice_label' => 'nicename',
                    'choice_value' => 'id'
                ])
                ->add('characters', CollectionType::class, [
                    'required' => false,
                    'allow_add' => true,
                    'by_reference' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'entry_type' => EntityType::class,
                    'entry_options' => [
                        'class' => Character::class,
                        'choice_label' => 'role_name',
                        'choice_value' => 'id',
                    ]
                ])
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('name')
            ->add('surname')
            ->add('birthDate', DateRangeFilter::class)
            ->add('nationality', ChoiceFilter::class, [
                'label' => 'Nationality',
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Country::class,
                    'choice_label' => 'nicename',
                ]
            ])
            ->add('movie', ChoiceFilter::class, [
                'label' => 'Movie',
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Movie::class,
                    'choice_label' => 'title',
                ]
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('name')
            ->add('surname')
            ->add('age', FieldDescriptionInterface::TYPE_STRING, [
                'accessor' => function (Actor $actor) {
                    return Carbon::now()->diffInYears($actor->getBirthDate());
                }
            ])
            ->add('nationality.nicename')
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
        // https://stackoverflow.com/questions/44669861/how-to-add-a-button-to-nav-bar
        // https://stackoverflow.com/questions/51744618/sonata-admin-configureformfields-add-action
        $show
            ->with('Picture', [
                'class' => 'col-md-4'
            ])
//                ->add('moviePicture', fieldDescriptionOptions: [
//                    'template' => 'admin/field/show_picture.html.twig',
//                ])
            ->end()
            ->with('Actor', [
                'class' => 'col-md-8'
            ])
                ->add('name')
                ->add('surname')
                ->add('age', FieldDescriptionInterface::TYPE_STRING, [
                    'accessor' => function (Actor $actor) {
                        return Carbon::now()->diffInYears($actor->getBirthDate());
                    }
                ])
                ->add('nationality.nicename', null, [
                    'label' => 'Nationality'
                ])
            ->end()
            ->with('Roles', [
                'class' => 'col-md-6'
            ])
                ->add('characters', fieldDescriptionOptions: [
                    'template' => 'admin/actor/field/show_names_collection.html.twig',
                ])
            ->end()
            ->with('Movies', [
                'class' => 'col-md-6'
            ])
                ->add('movies', fieldDescriptionOptions: [
                    'accessor' => function(Actor $actor) {
                        $movies = [];

                        foreach ($actor->getCharacters() as $character)
                        {
                            foreach ($character->getMovieCharacters() as $movieCharacter)
                            {
                                $movies[] = $movieCharacter->getMovie();
                            }
                        }

                        return array_unique($movies);
                    },
                    'template' => 'admin/actor/field/show_movies_names_collection.html.twig',
                ])
            ->end()
        ;
    }
}