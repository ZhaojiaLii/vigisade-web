<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="result")
 */
class Result
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey")
     * @ORM\JoinColumn()
     */
    private $survey;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="results")
     * @ORM\JoinColumn()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Direction")
     * @ORM\JoinColumn()
     */
    private $direction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area")
     * @ORM\JoinColumn()
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entity")
     * @ORM\JoinColumn()
     */
    private $entity;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $place;

    /**
     * @ORM\Column(type="text")
     */
    private $client;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bestPracticeDone;

    /**
     * @ORM\Column(type="text")
     */
    private $bestPracticeComment;

    /**
     * @ORM\Column(type="text")
     */
    private $bestPracticePhoto;

    /**
     * @var Collection|ResultTeamMember[]
     * @ORM\OneToMany(targetEntity="App\Entity\ResultTeamMember", mappedBy="result", cascade={"persist", "remove"})
     */
    private $teamMembers;

    /**
     * @var Collection|ResultQuestion[]
     * @ORM\OneToMany(targetEntity="App\Entity\ResultQuestion", mappedBy="result", cascade={"persist", "remove"})
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CorrectiveAction", mappedBy="result")
     */
    private $correctiveActions;

    public function __construct()
    {
        $this->teamMembers = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->correctiveActions = new ArrayCollection();
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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): Result
    {
        $this->date = $date;
        return $this;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    public function getValidated()
    {
        return $this->validated;
    }

    public function setValidated($validated)
    {
        $this->validated = $validated;
        return $this;
    }

    public function getBestPracticeDone()
    {
        return $this->bestPracticeDone;
    }

    public function setBestPracticeDone($bestPracticeDone)
    {
        $this->bestPracticeDone = $bestPracticeDone;
        return $this;
    }

    public function getBestPracticeComment()
    {
        return $this->bestPracticeComment;
    }

    public function setBestPracticeComment($bestPracticeComment)
    {
        $this->bestPracticeComment = $bestPracticeComment;
        return $this;
    }

    public function getBestPracticePhoto()
    {
        return $this->bestPracticePhoto;
    }

    public function setBestPracticePhoto($bestPracticePhoto)
    {
        $this->bestPracticePhoto = $bestPracticePhoto;
        return $this;
    }

    public function getTeamMembers()
    {
        return $this->teamMembers;
    }

    public function addTeamMember(ResultTeamMember $teamMember): self
    {
        if (!$this->teamMembers->contains($teamMember)) {
            $this->teamMembers[] = $teamMember;
            $teamMember->setResult($this);
        }

        return $this;
    }

    public function removeTeamMember(ResultTeamMember $teamMember): self
    {
        if ($this->teamMembers->contains($teamMember)) {
            $this->teamMembers->removeElement($teamMember);

            if ($teamMember->getResult() === $this) {
                $teamMember->setResult(null);
            }
        }

        return $this;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function addQuestion(ResultQuestion $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setResult($this);
        }

        return $this;
    }

    public function removeQuestion(ResultQuestion $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);

            if ($question->getResult() === $this) {
                $question->setResult(null);
            }
        }

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
            $correctiveAction->setResult($this);
        }

        return $this;
    }

    public function removeCorrectiveAction(CorrectiveAction $correctiveAction): self
    {
        if ($this->correctiveActions->contains($correctiveAction)) {
            $this->correctiveActions->removeElement($correctiveAction);
            // set the owning side to null (unless already changed)
            if ($correctiveAction->getResult() === $this) {
                $correctiveAction->setResult(null);
            }
        }

        return $this;
    }
}
