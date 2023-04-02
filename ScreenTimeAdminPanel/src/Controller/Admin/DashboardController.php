<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Actor\Controller\ActorCrudController;
use App\Actor\Entity\Actor;
use App\Character\Entity\Character;
use App\Country\Entity\Country;
use App\Genre\Entity\Genre;
use App\Movie\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * Name of the panel
     *
     * @var string
     */
    private string $panelName;

    public function __construct(string $panelName)
    {
        $this->panelName = $panelName;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 1. Make your dashboard redirect to the same page for all users
        return $this->redirect($adminUrlGenerator->setController(ActorCrudController::class)->generateUrl());

    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if(!$user instanceof UserInterface)
        {
            throw new \Exception("Wrong user");
        }

        return parent::configureUserMenu($user)
//            ->setAvatarUrl($user->getAvatarUrl())
//            ->setMenuItems([
//                MenuItem::linkToUrl('My Profile', 'fa fa-user', $this->generateUrl(
//                    'app_user_profile'
//                ))
//            ])
            ;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->panelName)
            ->setFaviconPath('favicon.svg')
            ->setLocales([
                'en' => '🇬🇧 English',
                'pl' => '🇵🇱 Polski'
            ]);
    }

    public function configureMenuItems(): iterable
    {
        // Main page
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // Api Data section
        yield MenuItem::section('Data');
        yield MenuItem::subMenu('API Data', 'fa fa-database')
            ->setSubItems([
                MenuItem::linkToCrud('Country', 'fa fa-map', Country::class),
                MenuItem::linkToCrud('Genre', 'fa fa-file-o', Genre::class),
                MenuItem::linkToCrud('Actors', 'fa fa-users', Actor::class),
                MenuItem::linkToCrud('Character', 'fa fa-institution', Character::class),
                MenuItem::linkToCrud('Movie', 'fa fa-video-camera', Movie::class),
            ])
        ;

        // Users managements section
        // TODO
    }
}
