<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorsRepository")
 */
class Authors
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $author_id;

    /**
     * @ORM\Column(type="string")
     */
    private $author_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $publication_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $share;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->author_name;
    }

    public function setAuthorName(string $author_name): self
    {
        $this->author_name = $author_name;

        return $this;
    }

    public function getPublicationId(): ?int
    {
        return $this->publication_id;
    }

    public function setPublicationId(int $publication_id): self
    {
        $this->publication_id = $publication_id;

        return $this;
    }

    public function getShare(): ?int
    {
        return $this->share;
    }

    public function setShare(int $share): self
    {
        $this->share = $share;

        return $this;
    }

    public function getIsAuthor(): ?bool
    {
        return $this->is_author;
    }

    public function setIsAuthor(bool $is_author): self
    {
        $this->is_author = $is_author;

        return $this;
    }
}
