<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class GenericCrudController extends AbstractCrudController
{
    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        switch($pageName)
        {
            case Crud::PAGE_INDEX:
            {
                return $this->configureIndexPageFields($pageName);
            }
            case Crud::PAGE_DETAIL:
            {
                return $this->configureDetailPageFields($pageName);
            }
            case Crud::PAGE_NEW:
            {
                return $this->configureNewPageFields($pageName);
            }
            case Crud::PAGE_EDIT:
            {
                return $this->configureEditPageFields($pageName);
            }
            default:
                throw new \Exception(sprintf('There is no mached page name to %s', $pageName));
        }
    }

    protected function configureIndexPageFields(string $pageName): iterable { return []; }

    protected function configureDetailPageFields(string $pageName): iterable { return []; }

    protected function configureNewPageFields(string $pageName): iterable { return []; }

    protected function configureEditPageFields(string $pageName): iterable { return $this->configureNewPageFields($pageName); }
}