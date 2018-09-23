<?php

namespace App\Entity\EntityTraits;

use Doctrine\ORM\Mapping as ORM;

trait Timestamp
{
    /**
     * @ORM\Column(type="datetime_immutable")
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @return void
     *
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->createdAt = \DateTimeImmutable::createFromMutable(new \DateTime());
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return void
     *
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
