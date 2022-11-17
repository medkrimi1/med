<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity=Candidates::class, mappedBy="country")
     */
    private $candidates;

    /**
     * @ORM\OneToMany(targetEntity=Jobs::class, mappedBy="country")
     */
    private $jobs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phonecode;

    public function __construct()
    {
        $this->candidates = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, Candidates>
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(Candidates $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
            $candidate->setCountry($this);
        }

        return $this;
    }

    public function removeCandidate(Candidates $candidate): self
    {
        if ($this->candidates->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getCountry() === $this) {
                $candidate->setCountry(null);
            }
        }

        return $this;
    }

    
 public function __toString() 
                        
                        
                            {
                          return $this->Name ;
                        
                            }

    /**
     * @return Collection<int, Jobs>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Jobs $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setCountry($this);
        }

        return $this;
    }

    public function removeJob(Jobs $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getCountry() === $this) {
                $job->setCountry(null);
            }
        }

        return $this;
    }

    public function getPhonecode(): ?string
    {
        return $this->phonecode;
    }

    public function setPhonecode(string $phonecode): self
    {
        $this->phonecode = $phonecode;

        return $this;
    }
}
