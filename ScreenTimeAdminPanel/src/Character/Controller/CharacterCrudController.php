<?php
declare(strict_types=1);

namespace App\Character\Controller;

use App\Character\Entity\Character;
use App\Controller\Admin\GenericCrudController;
use App\Share\Form\Type\CharacterInMovieType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class CharacterCrudController extends GenericCrudController
{
    public static function getEntityFqcn(): string
    {
        return Character::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('roleName')
            ->add('actor')
        ;
        // TODO add filter by movie???
    }

    protected function configureIndexPageFields(string $pageName): iterable
    {
        return [
            yield TextField::new('role_name', 'Role name'),
            yield TextField::new('actor.name', 'Actor name'),
        ];
    }

    protected function configureDetailPageFields(string $pageName): iterable
    {
        return [
            yield FormField::addTab('General Informations'),
            yield FormField::addPanel(),

            yield IdField::new('id', 'Id'),
            yield TextField::new('role_name', 'Role name'),
            yield TextField::new('actor.name', 'Actor name'),

            // TODO add info about user checking and confirming data

            yield FormField::addPanel('Movies')
                ->collapsible(),
            yield CollectionField::new('movies', 'Associated Movies')
                ->setTemplatePath('admin/Actor/fields/show_movie_collection.html.twig'),

        ];
    }

    protected function configureNewPageFields(string $pageName): iterable
    {
        return [
            yield TextField::new('role_name', 'Role name')
                ->setRequired(true),
            yield AssociationField::new('actor', 'Actor'),
            yield CollectionField::new('movieCharacters', 'Movies')
                ->setFormTypeOption('entry_type', CharacterInMovieType::class)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true),
        ];
    }

    protected function configureEditPageFields(string $pageName): iterable
    {
        return [
            yield TextField::new('role_name', 'Role name')
                ->setRequired(true),
            yield AssociationField::new('actor', 'Actor'),
            yield CollectionField::new('movieCharacters', 'Movies')
                ->setFormTypeOption('entry_type', CharacterInMovieType::class)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true),
        ];
    }
}
