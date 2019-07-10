<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyCategoryRepository")
 * @ORM\Table(name="survey_category")
 */
class SurveyCategory
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var Collection|SurveyQuestion[]
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyQuestion", mappedBy="category", cascade={"persist", "remove"})
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CorrectiveAction", mappedBy="category")
     */
    private $correctiveActions;

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $method = ('get' === substr($method, 0, 3) || 'set' === substr($method, 0, 3)) ? $method : 'get'. ucfirst($method);

        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get'. ucfirst($name);
        $arguments = [];

        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->correctiveActions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|SurveyQuestion[]
     */
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
            // set the owning side to null (unless already changed)
            if ($question->getCategory() === $this) {
                $question->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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
            $correctiveAction->setCategory($this);
        }

        return $this;
    }

    public function removeCorrectiveAction(CorrectiveAction $correctiveAction): self
    {
        if ($this->correctiveActions->contains($correctiveAction)) {
            $this->correctiveActions->removeElement($correctiveAction);
            // set the owning side to null (unless already changed)
            if ($correctiveAction->getCategory() === $this) {
                $correctiveAction->setCategory(null);
            }
        }

        return $this;
    }
}
