<?php

namespace App\Entity;

use App\Entity\EntityTraits\Activity;
use App\Entity\EntityTraits\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeMenuRepository")
 * @ORM\Table(name="menu_types")
 * @ORM\HasLifecycleCallbacks()
 */
class TypeMenu
{
    use Timestamp;
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
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    protected $code;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="typeMenu", cascade={"remove"})
     */
    protected $menu;

    public function __construct()
    {
        $this->menu = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @return TypeMenu
     */
    public function setName(string $name): TypeMenu
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null | string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param $code
     * @return TypeMenu
     */
    public function setCode($code): TypeMenu
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    /**
     * @param Menu $menu
     * @return TypeMenu
     */
    public function addMenu(Menu $menu): TypeMenu
    {
        $this->menu[] = $menu;

        return $this;
    }

    public function removeMenu(Menu $menu): bool
    {
        return $this->menu->removeElement($menu);
    }

    public function __toString()
    {
        return sprintf(
            '%s',
            $this->getName()
        );
    }
}
