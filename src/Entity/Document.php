<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\File(maxSize="60000"), mimeTypes={"application/pdf", "image/jpeg", "application/msword", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/zip"})
     */
    private $file;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @ORM\Column(name="is_public", type="boolean", nullable=false)
     */
    private $isPublic;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     */
    private $allowedUsers;


    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->allowedUsers = new ArrayCollection();
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAllowedUsers(): Collection
    {
        return $this->allowedUsers;
    }

    public function addAllowedUser(User $allowedUser): self
    {
        if (!$this->allowedUsers->contains($allowedUser)) {
            $this->allowedUsers[] = $allowedUser;
        }

        return $this;
    }

    public function removeAllowedUser(User $allowedUser): self
    {
        if ($this->allowedUsers->contains($allowedUser)) {
            $this->allowedUsers->removeElement($allowedUser);
        }

        return $this;
    }

    public function clearAllowedUser(): self
    {
        $this->allowedUsers->clear();

        return $this;
    }
    
    public function isAuthor(User $user = null)
    {
        return $user && $user->getId() === $this->getUser()->getId();
    }
}
