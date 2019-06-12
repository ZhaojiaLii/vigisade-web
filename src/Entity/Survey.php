<?php


namespace App\Entity;

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
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Direction")
     * @ORM\JoinColumn()
     */
    private $direction;

    /**
     * @ORM\Column(type="string")
     */
    private $team;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countTeam;

    /**
     * @ORM\Column(type="string")
     */
    private $bestPracticeLabel;

    /**
     * @ORM\Column(type="string")
     */
    private $bestPracticeHelp;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var Collection|SurveyCategory[]
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyCategory", mappedBy="survey")
     */
    private $categories;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getDirection()
    {
        return $this->direction;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function setTeam($team)
    {
        $this->team = $team;
        return $this;
    }

    public function getCountTeam()
    {
        return $this->countTeam;
    }

    public function setCountTeam($countTeam)
    {
        $this->countTeam = $countTeam;
        return $this;
    }

    public function getBestPracticeLabel()
    {
        return $this->bestPracticeLabel;
    }

    public function setBestPracticeLabel($bestPracticeLabel)
    {
        $this->bestPracticeLabel = $bestPracticeLabel;
        return $this;
    }

    public function getBestPracticeHelp()
    {
        return $this->bestPracticeHelp;
    }

    public function setBestPracticeHelp($bestPracticeHelp)
    {
        $this->bestPracticeHelp = $bestPracticeHelp;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): Survey
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

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

            if ($category->getSurvey() === $this) {
                $category->setSurvey(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
