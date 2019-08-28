<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 * @ORM\Table=(name="survey")
 */
class Survey
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $team;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countTeam;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyCategory", mappedBy="survey", cascade={"persist", "remove"})
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CorrectiveAction", mappedBy="survey")
     */
    private $correctiveActions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Direction", mappedBy="survey", cascade={"persist"})
     */
    private $direction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mailsAlertCorrectiveAction;

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
        $this->categories = new ArrayCollection();
        $this->correctiveActions = new ArrayCollection();
        $this->direction = new ArrayCollection();
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
            $correctiveAction->setSurvey($this);
        }

        return $this;
    }

    public function removeCorrectiveAction(CorrectiveAction $correctiveAction): self
    {
        if ($this->correctiveActions->contains($correctiveAction)) {
            $this->correctiveActions->removeElement($correctiveAction);
            // set the owning side to null (unless already changed)
            if ($correctiveAction->getSurvey() === $this) {
                $correctiveAction->setSurvey(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return Collection|Direction[]
     */
    public function getDirection(): Collection
    {
        return $this->direction;
    }

    public function addDirection(Direction $d): self
    {
        if (!$this->direction->contains($d)) {
            $this->direction[] = $d;
            $d->setSurvey($this);
        }

        return $this;
    }

    public function removeDirection(Direction $d): self
    {
        if ($this->direction->contains($d)) {
            $this->direction->removeElement($d);
            // set the owning side to null (unless already changed)
            if ($d->getSurvey() === $this) {
                $d->setSurvey(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMailsAlertCorrectiveAction()
    {
        return $this->mailsAlertCorrectiveAction;
    }

    /**
     * @param mixed $mailsAlertCorrectiveAction
     */
    public function setMailsAlertCorrectiveAction($mailsAlertCorrectiveAction): void
    {
        $this->mailsAlertCorrectiveAction = $mailsAlertCorrectiveAction;
    }
}
