<?php

namespace App\Entity;

use App\Repository\ApplicationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApplicationsRepository::class)
 */
class Applications
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Candidates::class, inversedBy="applications")
     */
    private $Candidate;

    /**
     * @ORM\ManyToOne(targetEntity=Jobs::class, inversedBy="applications")
     */
    private $job;

    /**
     * @ORM\Column(type="datetime")
     */
    private $appdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

     public function __construct()
    {
       
  
        $this->setAppdate(new \Datetime());
      
    }

  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidate(): ?Candidates
    {
        return $this->Candidate;
    }

    public function setCandidate(?Candidates $Candidate): self
    {
        $this->Candidate = $Candidate;

        return $this;
    }

    public function getJob(): ?Jobs
    {
        return $this->job;
    }

    public function setJob(?Jobs $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getAppdate(): ?\DateTimeInterface
    {
        return $this->appdate;
    }

    public function setAppdate(\DateTimeInterface $appdate): self
    {
        $this->appdate = $appdate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

  
   
}
