<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;

/**
 * @ORM\Entity("")
 */
class SurveyTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $bestPracticeLabel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bestPracticeHelp;


    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBestPracticeLabel(): ?string
    {
        return $this->bestPracticeLabel;
    }

    public function setBestPracticeLabel(string $bestPracticeLabel): self
    {
        $this->bestPracticeLabel = $bestPracticeLabel;

        return $this;
    }

    public function getBestPracticeHelp(): ?string
    {
        return $this->bestPracticeHelp;
    }

    public function setBestPracticeHelp(?string $bestPracticeHelp): self
    {
        $this->bestPracticeHelp = $bestPracticeHelp;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
