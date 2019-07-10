<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeDangerousSituationRepository")
 */
class TypeDangerousSituation
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
