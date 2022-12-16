<?php

namespace App\Entity;

use App\Repository\WorkExperienceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkExperienceRepository::class)
 */
class WorkExperience
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
    private $company;

    /**
      * @ORM\Column(type="datetime")
     */
    private $startdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enddate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $envi;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $duties;

    /**
     * @ORM\ManyToOne(targetEntity=Candidates::class, inversedBy="workExperiences")
     */
    private $candidate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $project;

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

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

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

    public function setEnddate(?string $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getEnvi(): ?string
    {
        return $this->envi;
    }

    public function setEnvi(?string $envi): self
    {
        $this->envi = $envi;

        return $this;
    }

    public function getDuties(): ?string
    {
        return $this->duties;
    }

    public function setDuties(?string $duties): self
    {
        $this->duties = $duties;

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

    public function getProject(): ?string
    {
        return $this->project;
    }

    public function setProject(?string $project): self
    {
        $this->project = $project;

        return $this;
    }
}
