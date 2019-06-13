<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="survey_category")
 */
class SurveyCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="categories")
     * @ORM\JoinColumn()
     */
    private $survey;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var Collection|SurveyQuestion[]
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyQuestion", mappedBy="category", cascade={"persist"})
     */
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getSurvey()
    {
        return $this->survey;
    }

    public function setSurvey($survey)
    {
        $this->survey = $survey;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): SurveyCategory
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(SurveyQuestion $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setCategory($this);
        }

        return $this;
    }

    public function removeQuestion(SurveyQuestion $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            if ($question->getCategory() === $this) {
                $question->setCategory(null);
            }
        }

        return $this;
    }
}
