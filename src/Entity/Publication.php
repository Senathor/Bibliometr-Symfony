<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicationRepository")
 */
class Publication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @var datetime A "Y-m-d" formatted value
     */
    private $publication_date;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $timezone;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $magazine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $conference;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $url;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function clearUsers(): self
    {
        $this->users = [];

        return $this;
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


    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function getTimezone(): ?string
    {
        return $this->publication_date->getTimeZone()->getName();
    }

    public function setPublicationDate(\DateTimeInterface $publication_date): self
    {
        $this->publication_date = $publication_date;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getMagazine(): ?string
    {
        return $this->magazine;
    }

    public function setMagazine(?string $magazine): self
    {
        $this->magazine = $magazine;

        return $this;
    }

    public function getConference(): ?string
    {
        return $this->conference;
    }

    public function setConference(?string $conference): self
    {
        $this->conference = $conference;

        return $this;
    }
    public function removeConference(): self
    {
        $this->conference->clear();

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
