<?php

namespace App\Menu;

use App\Entity\Menu;
use App\Entity\TypeMenu;
use App\Repository\TypeMenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    /** @var FactoryInterface */
    private $factory;

    /** @var ContainerInterface */
    private $container;

    /** @var EntityManagerInterface */
    private $em;

    /** @var TypeMenuRepository */
    private $typeMenuRepository;

    /**
     * @param FactoryInterface $factory
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        FactoryInterface $factory,
        ContainerInterface $container,
        EntityManagerInterface $entityManager
    ) {
        $this->factory = $factory;
        $this->container = $container;
        $this->em = $entityManager;
        $this->typeMenuRepository = $this->em->getRepository(TypeMenu::class);
    }

    public function createMainMenu()
    {
        $menuItems = $this->getMainMenu();
        $menu = $this->factory->createItem('root');

        $this->setCurrentItem($menu);

        $menu->setChildrenAttribute('class', 'uk-navbar-nav');
        $menu->setExtra('currentElement', 'active');

        /** @var Menu $item */
        foreach ($menuItems as $item) {
            if ($item->isParent()) {
                $menu->addChild($item->getName(), ['uri' => $item->getRoute()]);
                /** @var Menu $child */
                foreach ($item->getChildren() as $child) {
                    $menu[$item->getName()]
                        ->setChildrenAttribute('class', 'uk-nav uk-navbar-dropdown-nav')
                        ->addChild($child->getName(), ['uri' => $child->getRoute()]);
                }
            }
        }

        return $menu;
    }

    protected function setCurrentItem(ItemInterface $menu)
    {
        /** @var RequestStack $requestStack */
        $requestStack = $this->container->get('request_stack');

        $menu->setUri($requestStack->getCurrentRequest()->getPathInfo());
    }

    public function getMainMenu()
    {
        return $this->typeMenuRepository->getMainMenu();
    }
}
