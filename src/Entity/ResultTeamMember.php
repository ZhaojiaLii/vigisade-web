<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="result_team_member")
 */
class ResultTeamMember
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
    private $firstName;

    /**
     * @ORM\Column(type="text")
     */
    private $lastName;

    /**
     * @ORM\Column(type="text")
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ResultQuestion", mappedBy="teamMembers")
     */
    private $resultQuestions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Result", inversedBy="teamMembers")
     */
    private $result;

    /**
     * ResultTeamMember constructor.
     */
    public function __construct()
    {
        $this->resultQuestions = new ArrayCollection();
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

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return Collection|ResultQuestion[]
     */
    public function getResultQuestions(): Collection
    {
        return $this->resultQuestions;
    }

    public function addResultQuestion(ResultQuestion $resultQuestion): self
    {
        if (!$this->resultQuestions->contains($resultQuestion)) {
            $this->resultQuestions[] = $resultQuestion;
            $resultQuestion->setTeamMembers($this);
        }

        return $this;
    }

    public function removeResultQuestion(ResultQuestion $resultQuestion): self
    {
        if ($this->resultQuestions->contains($resultQuestion)) {
            $this->resultQuestions->removeElement($resultQuestion);
            // set the owning side to null (unless already changed)
            if ($resultQuestion->getTeamMembers() === $this) {
                $resultQuestion->setTeamMembers(null);
            }
        }

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

}
