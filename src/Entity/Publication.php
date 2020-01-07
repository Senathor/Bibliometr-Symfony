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
     * @ORM\Column(type="string", length=512)
     */
    private $authors;

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
     * @ORM\Column(type="string", length=512)
     */
    private $shares;

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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="publications", orphanRemoval=true)
     * @ORM\JoinTable(name="publications_list")
     */
    private $users = [];

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addUsers(?User $users): self
    {
        $usrs = is_array($this->users) ? $this->users : $this->users->toArray();
        if (in_array($users, $usrs)) {
            return $this;
        }

        $this->users[] = $users;
        $users->addPublications($this);

        return $this;
    }

    public function removeUsers(?User $users): self
    {
        $usrs = is_array($this->users) ? $this->users : $this->users->toArray();
        if (!in_array($users, $usrs)) {
            return $this;
        }

        $this->users->removeElement($users);
        $users->removePublications($this);

        return $this;
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

    public function getAuthors(): ?string
    {
        return $this->authors;
    }

    public function setAuthors(string $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function getTimezone(): ?string
    {
        return $this->publication_date->getTimeZone()->getName();;
    }

    public function setPublicationDate(\DateTimeInterface $publication_date): self
    {
        $this->publication_date = $publication_date;

        return $this;
    }

    public function getShares(): ?string
    {
        return $this->shares;
    }

    public function setShares(string $shares): self
    {
        $this->shares = $shares;

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
