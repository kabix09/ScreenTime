<?php

declare(strict_types=1);

namespace App\Character\Admin;

use App\Actor\Entity\Actor;
use App\Character\Entity\Character;
use App\Character\Form\CharacterInMovieType;
use App\Movie\Entity\Movie;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\DoctrineORMAdminBundle\Filter\NumberFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CharacterAdmin extends AbstractAdmin
{
    private const ADMIN_ID = 'character';

    protected $baseRoutePattern;

    public function __construct(?string $code = null, ?string $class = null, ?string $baseControllerName = null)
    {
        $this->baseRoutePattern = self::ADMIN_ID;

        parent::__construct($code, $class, $baseControllerName);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('roleName', TextType::class, [
                'label' => 'Role Name'
            ])
            ->add('actor', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => function(Actor $actor) { return sprintf('%s %s', $actor->getName(), $actor->getSurname()); },
                'choice_value' => 'id'
            ])
            ->add('movieCharacters', CollectionType::class, [
                'label' => 'Movies',
                'required' => false,
                'by_reference' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'entry_type' => CharacterInMovieType::class,
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('roleName')
            ->add('actor', ChoiceFilter::class, [
                'label' => 'Actor',
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Actor::class,
                    'choice_label' => function(Actor $actor) { return sprintf('%s %s', $actor->getName(), $actor->getSurname()); },
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
            ->add('movieCharacters.timeOnScene', NumberFilter::class, [
                'label' => 'Time on scene'
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('roleName')
            ->add('actor')
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
        $show
            ->with('Picture', [
                'class' => 'col-md-4'
            ])
//                ->add('moviePicture', fieldDescriptionOptions: [
//                    'template' => 'admin/field/show_picture.html.twig',
//                ])
            ->end()
            ->with('Character', [
                'class' => 'col-md-8'
            ])
                ->add('roleName')
                ->add('actor.name', null, [
                    'label' => 'Actor',
                    'template' => 'admin/actor/field/admin_actor_link.html.twig',
// OPTIONALLY FOR PASSING DATA TO TEMPLATE
//      or we can set variable in this class and read in template by admin.my_var
//                    'data' => [
//                    ]
                ])
            ->end()
            ->with('', [
                'class' => 'col-md-4'
            ])
            ->end()
            ->with('Movies', [
                'class' => 'col-md-6'
            ])
                ->add('movies', fieldDescriptionOptions: [
                    'accessor' => function(Character $character) {
                        return $character->getMovieCharacters();
                    },
                    'template' => 'admin/actor/field/show_movies_names_collection.html.twig',
                ])
            ->end()
        ;
    }


    /*
     * OVERWRITE EVENTS FUNCTIONS
     */

    /**
     * @param Character $object
     * @return void
     */
    public function prePersist($object): void
    {
        foreach ($object->getMovieCharacters() as &$movieCharacter) {
            $movieCharacter->setCharacter($object);
        }
    }

    /**
     * @param Character $object
     * @return void
     */
    public function preUpdate($object): void
    {
        foreach ($object->getMovieCharacters() as &$movieCharacter) {
            $movieCharacter->setCharacter($object);
        }
    }
}