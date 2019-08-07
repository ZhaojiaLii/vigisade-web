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
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="correctiveActions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveyQuestion", inversedBy="correctiveActions")
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentQuestion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Result", inversedBy="correctiveActions")
     */
    private $result;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ResultQuestion", inversedBy="correctiveActions")
     */
    private $resultQuestion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Direction", inversedBy="correctiveActions")
     */
    private $direction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="correctiveActions")
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entity", inversedBy="correctiveActions")
     */
    private $entity;

    /**
     * CorrectiveAction constructor.
     */
    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestion(): ?SurveyQuestion
    {
        return $this->question;
    }

    public function setQuestion(?SurveyQuestion $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCommentQuestion(): ?string
    {
        return $this->commentQuestion;
    }

    public function setCommentQuestion(?string $commentQuestion): self
    {
        $this->commentQuestion = $commentQuestion;

        return $this;
    }

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): self
    {
        $this->area = $area;

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

    public function getEntity(): ?Entity
    {
        return $this->entity;
    }

    public function setEntity(?Entity $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function getResultQuestion(): ?ResultQuestion
    {
        return $this->resultQuestion;
    }

    public function setResultQuestion(?ResultQuestion $resultQuestion): self
    {
        $this->resultQuestion = $resultQuestion;

        return $this;
    }
}
