<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyQuestionRepository")
 * @ORM\Table(name="survey_question")
 */
class SurveyQuestion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $label;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $help;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveyCategory", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getHelp(): ?string
    {
        return $this->help;
    }

    public function setHelp(?string $help): self
    {
        $this->help = $help;

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

}
