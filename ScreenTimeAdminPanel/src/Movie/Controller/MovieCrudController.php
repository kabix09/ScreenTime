<?php
declare(strict_types=1);

namespace App\Movie\Controller;

use App\Controller\Admin\GenericCrudController;
use App\Movie\Entity\Movie;
use App\Share\Form\Type\CharacterInMovieType;
use App\Share\Form\Type\MovieCharacterType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MovieCrudController extends GenericCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add('productionYear')
            ->add('durationTime')
            ->add('worldPremiereDate')
            ->add('movieGenre')
        ;
        // TODO add filter by characters & actors(maybe) ???
    }

    protected function configureIndexPageFields(string $pageName): iterable
    {
        return [
            yield TextField::new('title', 'Title'),
            yield DateField::new('productionYear', 'Production year'),
        ];
    }

    protected function configureDetailPageFields(string $pageName): iterable
    {
        return [
            yield FormField::addTab('General Informations'),
            yield FormField::addPanel(),

            yield IdField::new('id', 'Id'),
            yield TextField::new('title', 'Title'),
            yield IntegerField::new('durationTime', 'Duration time'),
            yield DateField::new('worldPremiereDate', 'Premiere'),
            yield DateField::new('productionYear', 'Production year'),
            yield IntegerField::new('charactersAmount'),

            yield FormField::addTab('Characters'),
            yield FormField::addPanel(),

            yield CollectionField::new('movieCharacters', 'Movie Characters')
                ->setTemplatePath('admin/Movie/fields/show_character_collection.html.twig'),

        ];
    }

    protected function configureNewPageFields(string $pageName): iterable
    {
        return [
            yield TextField::new('title', 'Title')
                ->setRequired(true),
            yield DateField::new('productionYear', 'Production year'),
            yield IntegerField::new('durationTime', 'Duration time'),
            yield DateField::new('worldPremiereDate', 'Premiere'),
            yield CollectionField::new('movieCharacters', 'Movies')
                ->setFormTypeOption('entry_type', MovieCharacterType::class)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true),
        ];
    }

    protected function configureEditPageFields(string $pageName): iterable
    {
        return [
            yield TextField::new('title', 'Title')
                ->setRequired(true),
            yield DateField::new('productionYear', 'Production year'),
            yield IntegerField::new('durationTime', 'Duration time'),
            yield DateField::new('worldPremiereDate', 'Premiere'),
            yield CollectionField::new('movieCharacters', 'Movies')
                ->setFormTypeOption('entry_type', MovieCharacterType::class)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true),
        ];
    }
}
