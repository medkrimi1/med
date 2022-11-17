<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 */
class Language
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $speak;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reading;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $writing;

    /**
     * @ORM\ManyToOne(targetEntity=Candidates::class, inversedBy="languages")
     */
    private $candidate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lname;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpeak(): ?string
    {
        return $this->speak;
    }

    public function setSpeak(?string $speak): self
    {
        $this->speak = $speak;

        return $this;
    }

    public function getReading(): ?string
    {
        return $this->reading;
    }

    public function setReading(?string $reading): self
    {
        $this->reading = $reading;

        return $this;
    }

    public function getWriting(): ?string
    {
        return $this->writing;
    }

    public function setWriting(?string $writing): self
    {
        $this->writing = $writing;

        return $this;
    }

    public function getCandidate(): ?Candidates
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidates $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(?string $lname): self
    {
        $this->lname = $lname;

        return $this;
    }
}
