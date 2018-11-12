<?php

namespace App\Entity;

use App\Entity\EntityTraits\Activity;
use App\Entity\EntityTraits\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 * @ORM\Table(name="menu")
 * @ORM\HasLifecycleCallbacks()
 */
class Menu
{
    use Timestamp {
        prePersist as public timestampPrePersist;
        preUpdate as public timestampPreUpdate;
    }
    use Activity;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $route;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $static;

    /**
     * @var TypeMenu
     *
     * @ORM\ManyToOne(targetEntity="TypeMenu", inversedBy="menu")
     */
    protected $typeMenu;

    /**
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
     */
    protected $parent;

    /**
     * @var Menu
     *
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent", cascade={"remove"})
     */
    protected $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return int | null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string | null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Menu
     */
    public function setName(string $name): Menu
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return Menu
     */
    public function setRoute(string $route): Menu
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return bool | null
     */
    public function isStatic(): ?bool
    {
        return $this->static;
    }

    /**
     * @param bool $static
     * @return Menu
     */
    public function setStatic(bool $static): Menu
    {
        $this->static = $static;

        return $this;
    }

    /**
     * @return TypeMenu|null
     */
    public function getTypeMenu(): ?TypeMenu
    {
        return $this->typeMenu;
    }

    /**
     * @param TypeMenu $typeMenu
     * @return Menu
     */
    public function setTypeMenu(TypeMenu $typeMenu): Menu
    {
        $this->typeMenu = $typeMenu;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChildren(Menu $menu): Menu
    {
        $this->children[] = $menu;

        return $this;
    }

    public function removeChildren(Menu $menu): bool
    {
        return $this->children->removeElement($menu);
    }

    /**
     * @return Menu | null
     */
    public function getParent(): ?Menu
    {
        return $this->parent;
    }

    /**
     * @param Menu | null    $parent
     */
    public function setParent(?Menu $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return bool
     */
    public function isParent(): bool
    {
        if (count($this->getChildren()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isFirstLvl(): bool
    {
        if ($this->getParent()) {
            return false;
        }

        return true;
    }

    public function __toString()
    {
        return sprintf(
            '%s (%s)',
            $this->getName(),
            $this->getRoute()
        );
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->timestampPrePersist();
        if ($this->isStatic()) {
            $this->route = 'custom/' . $this->route;
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->timestampPreUpdate();
        if ($this->isStatic()) {
            $this->route = 'custom/' . $this->route;
        }
    }
}
