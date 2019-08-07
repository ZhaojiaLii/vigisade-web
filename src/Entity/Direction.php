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
    private $areas;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="direction")
     */
    private $survey;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CorrectiveAction", mappedBy="direction")
     */
    private $correctiveActions;


    public function __construct()
    {
        $this->areas = new ArrayCollection();
        $this->correctiveActions = new ArrayCollection();
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

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Area[]
     */
    public function getAreas(): Collection
    {
        return $this->areas;
    }

    public function addArea(Area $area): self
    {
        if (!$this->areas->contains($area)) {
            $this->areas[] = $area;
            $area->setDirection($this);
        }

        return $this;
    }

    public function removeArea(Area $area): self
    {
        if ($this->areas->contains($area)) {
            $this->areas->removeElement($area);
            // set the owning side to null (unless already changed)
            if ($area->getDirection() === $this) {
                $area->setDirection(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return Collection|CorrectiveAction[]
     */
    public function getCorrectiveActions(): Collection
    {
        return $this->correctiveActions;
    }

    public function addCorrectiveAction(CorrectiveAction $correctiveAction): self
    {
        if (!$this->correctiveActions->contains($correctiveAction)) {
            $this->correctiveActions[] = $correctiveAction;
            $correctiveAction->setDirection($this);
        }

        return $this;
    }

    public function removeCorrectiveAction(CorrectiveAction $correctiveAction): self
    {
        if ($this->correctiveActions->contains($correctiveAction)) {
            $this->correctiveActions->removeElement($correctiveAction);
            // set the owning side to null (unless already changed)
            if ($correctiveAction->getDirection() === $this) {
                $correctiveAction->setDirection(null);
            }
        }

        return $this;
    }
}
