<?php

namespace FinquesFarnos\AppBundle\Menu;

use FinquesFarnos\AppBundle\Service\InmoebreUriLocaleService;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class MenuBuilder.
 *
 * @category Menu
 *
 * @author   David Romaní <david@flux.cat>
 */
class FrontendMenu
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $ac;

    /**
     * @var TokenStorageInterface
     */
    private $ts;

    /**
     * @var InmoebreUriLocaleService
     */
    private $iuls;

    /**
     * Contructor.
     *
     * @param FactoryInterface              $factory
     * @param Translator                    $translator
     * @param AuthorizationCheckerInterface $ac
     * @param TokenStorageInterface         $ts
     * @param InmoebreUriLocaleService      $iuls
     */
    public function __construct(FactoryInterface $factory, Translator $translator, AuthorizationCheckerInterface $ac, TokenStorageInterface $ts, InmoebreUriLocaleService $iuls)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->ac = $ac;
        $this->ts = $ts;
        $this->iuls = $iuls;
    }

    /**
     * Create main menu.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->setChildrenAttribute('id', 'nav-accordion');
        $menu->setExtras(array('firstClass' => null));

        $menu->addChild('home', array(
                'label' => $this->translator->trans('menu.home'),
                'route' => 'front_homepage',
            ));
        $menu->addChild('immoebre', array(
                'label' => $this->translator->trans('menu.immoebre'),
                'uri' => $this->iuls->getUri(),
                'linkAttributes' => array('target' => '_blank'),
            ));
        $menu->addChild('properties', array(
                'label' => $this->translator->trans('menu.properties'),
                'route' => 'front_properties',
                'current' => $request->get('_route') == 'front_properties' || $request->get('_route') == 'front_property',
            ));
        $menu->addChild('about', array(
                'label' => $this->translator->trans('menu.about'),
                'route' => 'front_about',
            ));
        $menu->addChild('contact', array(
                'label' => $this->translator->trans('menu.contact'),
                'route' => 'front_contact',
            ));
        if ($this->ts->getToken() && $this->ac->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu->addChild('admin', array(
                'label' => '<i class="fa fa-cog"></i>',
                'route' => 'sonata_admin_dashboard',
                'extras' => array('safe_label' => true),
            ));
            $menu->addChild('logout', array(
                'label' => '<i class="fa fa-power-off"></i>',
                'route' => 'sonata_user_admin_security_logout',
                'extras' => array('safe_label' => true),
            ));
        }

        return $menu;
    }
}
