<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CorrectiveActionRepository")
 */
class CorrectiveAction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="actionCorrective")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="correctiveActions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveyCategory", inversedBy="correctiveActions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveyQuestion", inversedBy="correctiveActions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateControlle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $placeConstruction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;


    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateControlle(): ?\DateTimeInterface
    {
        return $this->dateControlle;
    }

    public function setDateControlle(\DateTimeInterface $dateControlle): self
    {
        $this->dateControlle = $dateControlle;

        return $this;
    }

    public function getPlaceConstruction(): ?string
    {
        return $this->placeConstruction;
    }

    public function setPlaceConstruction(string $placeConstruction): self
    {
        $this->placeConstruction = $placeConstruction;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getCategory(): ?SurveyCategory
    {
        return $this->category;
    }

    public function setCategory(?SurveyCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getQuestion(): ?SurveyQuestion
    {
        return $this->question;
    }

    public function setQuestion(?SurveyQuestion $question): self
    {
        $this->question = $question;

        return $this;
    }
}
