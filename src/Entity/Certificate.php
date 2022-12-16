<?php

namespace App\Entity;

use App\Repository\CertificateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CertificateRepository::class)
 */
class Certificate
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attachment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startdate;

    /**
      * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enddate;
	/**
     * @ORM\ManyToOne(targetEntity=Candidates::class, inversedBy="workExperiences")
     */
    private $candidate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(?string $attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getStartdate():?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?string 
    {
        return $this->enddate;
    }

    public function setEnddate(?string  $enddate): self
    {
        $this->enddate = $enddate;

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
}
