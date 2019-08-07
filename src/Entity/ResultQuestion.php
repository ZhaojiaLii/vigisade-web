<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="result_question")
 */
class ResultQuestion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Result", inversedBy="questions")
     * @ORM\JoinColumn()
     */
    private $result;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveyQuestion")
     * @ORM\JoinColumn()
     */
    private $question;

    /**
     * @ORM\Column(type="integer")
     */
    private $notation;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ResultTeamMember", inversedBy="resultQuestions")
     */
    private $teamMembers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CorrectiveAction", mappedBy="resultQuestion")
     */
    private $correctiveActions;

    public function __construct()
    {
        $this->correctiveActions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    public function getNotation()
    {
        return $this->notation;
    }

    public function setNotation($notation)
    {
        $this->notation = $notation;
        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    public function getTeamMembers(): ?ResultTeamMember
    {
        return $this->teamMembers;
    }

    public function setTeamMembers(?ResultTeamMember $teamMembers): self
    {
        $this->teamMembers = $teamMembers;

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
            $correctiveAction->setResultQuestion($this);
        }

        return $this;
    }

    public function removeCorrectiveAction(CorrectiveAction $correctiveAction): self
    {
        if ($this->correctiveActions->contains($correctiveAction)) {
            $this->correctiveActions->removeElement($correctiveAction);
            // set the owning side to null (unless already changed)
            if ($correctiveAction->getResultQuestion() === $this) {
                $correctiveAction->setResultQuestion(null);
            }
        }

        return $this;
    }

}
