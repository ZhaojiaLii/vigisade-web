<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyQuestionRepository")
 * @ORM\Table(name="survey_question")
 */
class SurveyQuestion
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveyCategory", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $questionType;

    /**
     * @ORM\Column(type="integer")
     */
    private $questionOrder;

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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestionType(): ?string
    {
        return $this->questionType;
    }

    public function setQuestionType(string $questionType): self
    {
        $this->questionType = $questionType;

        return $this;
    }

    public function getQuestionOrder(): ?int
    {
        return $this->questionOrder;
    }

    public function setQuestionOrder(int $questionOrder): self
    {
        $this->questionOrder = $questionOrder;

        return $this;
    }

}
