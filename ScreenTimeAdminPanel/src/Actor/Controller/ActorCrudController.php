<?php
declare(strict_types=1);

namespace App\Actor\Controller;

use App\Actor\Entity\Actor;
use App\Controller\Admin\GenericCrudController;
use App\Share\Form\Type\CharacterSelectType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ActorCrudController extends GenericCrudController
{
    public static function getEntityFqcn(): string
    {
        return Actor::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('surname')
            ->add('birthDate')
            ->add('nationality')
            ->add('characters')
        ;
    }


    protected function configureIndexPageFields(string $pageName): iterable
    {
        return [
            yield IdField::new('id'),
            yield TextField::new('name'),
            yield TextField::new('surname'),
            yield DateField::new('birth_date'),
            yield TextField::new('nationality.nicename', 'Nationality'),
            yield IntegerField::new('charactersAmount', 'Played Roles')
        ];
    }

    protected function configureDetailPageFields(string $pageName): iterable
    {
        return [
            yield FormField::addTab('General Informations'),
            yield FormField::addPanel(),

            yield IdField::new('id', 'Id'),
            yield TextField::new('name'),
            yield IntegerField::new('age'),
            yield DateField::new('birth_date'),
            yield TextField::new('nationality.nicename', 'Nationality'),

            yield FormField::addPanel('Roles')
                ->setIcon('fa fa-users')->addCssClass('optional')
                ->setHelp('List of played roles')
                ->renderCollapsed(),

            yield CollectionField::new('characters')
                ->setTemplatePath('admin/Actor/fields/show_character_collection.html.twig'),


            // TODO - - - co jesli jedna postac grała kilka ról w tym samym filmie
            // bedzie on wyświetlany na liście w wid. kilkukrotnie
            yield FormField::addTab('Movies'),
            yield CollectionField::new('movies')
                ->setTemplatePath('admin/Actor/fields/show_movie_collection.html.twig'),
        ];
    }

    protected function configureNewPageFields(string $pageName): iterable
    {
        return [
            yield TextField::new('name'),
            yield TextField::new('surname'),
            yield DateField::new('birth_date')
                ->setRequired(true),
//            yield CollectionField::new('characters')
//                ->useEntryCrudForm(CharacterCrudController::class)

            yield CollectionField::new('characters')
                ->setFormTypeOption('entry_type', CharacterSelectType::class)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true),
        ];
    }

    protected function configureEditPageFields(string $pageName): iterable
    {
        return [
            yield TextField::new('name'),
            yield TextField::new('surname'),
            yield DateField::new('birth_date'),
//            yield CollectionField::new('characters')
//                ->useEntryCrudForm(CharacterCrudController::class)

            yield CollectionField::new('characters')
                ->setFormTypeOption('entry_type', CharacterSelectType::class)
                ->setFormTypeOption('entry_options', ['model' => $this->getContext()->getEntity()->getInstance()])
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true),
        ];
    }
}
