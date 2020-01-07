<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Użytkownik z takim emailem już istnieje!")
 */
class User implements UserInterface
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
    private $name;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $afiliacja;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Publication", inversedBy="users", orphanRemoval=true)
     * @ORM\JoinTable(name="publications_list")
     */
    private $publications;

    public function getPublications()
    {
        return $this->publications;
    }

    public function addPublications(?Publication $publications): self
    {
        $pubs = is_array($this->publications) ? $this->publications : $this->publications->toArray();
        if (in_array($publications, $pubs)) {
            return $this;
        }

        $this->publications[] = $publications;
        $publications->addUsers($this);

        return $this;
    }

    public function removePublications(?Publication $publications): self
    {
        $pubs = is_array($this->publications) ? $this->publications : $this->publications->toArray();
        if (in_array($publications, $pubs)) {
            return $this;
        }

        $this->publications->removeElement($publications);
        // $publications->removeUsers($this);

        return $this;
    }

    public function clearPublications(): self
    {
        $this->publications = [];
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAfiliacja(): ?string
    {
        return $this->afiliacja;
    }

    public function setAfiliacja(string $afiliacja): self
    {
        $this->afiliacja = $afiliacja;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    public function getUsername()
    {
        return $this->name;
    }
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}
