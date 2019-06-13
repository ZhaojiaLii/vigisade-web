<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 * @ORM\Table=(name="survey")
 */
class Survey
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Direction", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $direction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $team;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countTeam;

    /**
     * @ORM\Column(type="text")
     */
    private $bestPracticeLabel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bestPracticeHelp;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyCategory", mappedBy="survey", cascade={"persist", "remove"})
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(string $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getCountTeam(): ?int
    {
        return $this->countTeam;
    }

    public function setCountTeam(?int $countTeam): self
    {
        $this->countTeam = $countTeam;

        return $this;
    }

    public function getBestPracticeLabel(): ?string
    {
        return $this->bestPracticeLabel;
    }

    public function setBestPracticeLabel(string $bestPracticeLabel): self
    {
        $this->bestPracticeLabel = $bestPracticeLabel;

        return $this;
    }

    public function getBestPracticeHelp(): ?string
    {
        return $this->bestPracticeHelp;
    }

    public function setBestPracticeHelp(?string $bestPracticeHelp): self
    {
        $this->bestPracticeHelp = $bestPracticeHelp;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDirection(): ?Direction
    {
        return $this->direction;
    }

    public function setDirection(?Direction $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return Collection|SurveyCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(SurveyCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setSurvey($this);
        }

        return $this;
    }

    public function removeCategory(SurveyCategory $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getSurvey() === $this) {
                $category->setSurvey(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
