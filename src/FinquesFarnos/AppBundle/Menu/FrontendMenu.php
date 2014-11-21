<?php

namespace FinquesFarnos\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MenuBuilder
 *
 * @category Menu
 * @package  FinquesFarnos\AppBundle\Menu
 * @author   David Romaní <david@flux.cat>
 */
class FrontendMenu
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Create main menu
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

        $menu->addChild('Home', array('route' => 'front_homepage'));
        $menu->addChild('Properties', array('route' => 'front_properties'));
        $menu->addChild('About us', array('route' => 'front_homepage'));
        $menu->addChild('Contact', array('route' => 'front_homepage'));

        return $menu;
    }
}
