<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DirectionRepository")
 */
class Direction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Area", mappedBy="direction")
     */
    private $Areas;


    public function __construct()
    {
        $this->Areas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Area[]
     */
    public function getAreas(): Collection
    {
        return $this->Areas;
    }

    public function addArea(Area $area): self
    {
        if (!$this->Areas->contains($area)) {
            $this->Areas[] = $area;
            $area->setDirection($this);
        }

        return $this;
    }

    public function removeArea(Area $area): self
    {
        if ($this->Areas->contains($area)) {
            $this->Areas->removeElement($area);
            // set the owning side to null (unless already changed)
            if ($area->getDirection() === $this) {
                $area->setDirection(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
