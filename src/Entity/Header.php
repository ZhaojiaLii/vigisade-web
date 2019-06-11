<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HeaderRepository")
 * @Vich\Uploadable
 */
class Header
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
    private $logoText;

    /**
     * @ORM\Column(type="text")
     */
    private $news;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="header_images", fileNameProperty="image")
     * @var File
     */
    private $logoImage;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogoText(): ?string
    {
        return $this->logoText;
    }

    public function setLogoText(string $logoText): self
    {
        $this->logoText = $logoText;

        return $this;
    }

    public function getNews(): ?string
    {
        return $this->news;
    }

    public function setNews(string $news): self
    {
        $this->news = $news;

        return $this;
    }

    public function setLogoImage(File $image = null)
    {
        $this->logoImage = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getLogoImage()
    {
        return $this->logoImage;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
