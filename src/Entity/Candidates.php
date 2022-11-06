<?php

namespace App\Entity;
use App\Entity\Country;
use App\Repository\CandidatesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidatesRepository::class)
 */
class Candidates
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
    private $fname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lname;


  

    /**
     * @ORM\ManyToOne(targetEntity=Professions::class, inversedBy="candidates")
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=Candidatexp::class, inversedBy="candidates")
     */
    private $experience;

   

    /**
     * @ORM\ManyToMany(targetEntity=Skills::class, inversedBy="candidates")
     */
    private $skill;

    /**
     * @ORM\OneToMany(targetEntity=Applications::class, mappedBy="Candidate")
     */
    private $applications;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="candidates")
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $seen;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;

  
   
 

    public function __construct()
    {
       
      
        $this->skill = new ArrayCollection();
        $this->applications = new ArrayCollection();
       
      
       
    }

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;

        return $this;
    }




   

    public function getTitre(): ?Professions
    {
        return $this->titre;
    }

    public function setTitre(?Professions $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getExperience(): ?Candidatexp
    {
        return $this->experience;
    }

    public function setExperience(?Candidatexp $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

  

    /**
     * @return Collection<int, Skills>
     */
    public function getSkill(): Collection
    {
        return $this->skill;
    }

    public function addSkill(Skills $skill): self
    {
        if (!$this->skill->contains($skill)) {
            $this->skill[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skills $skill): self
    {
        $this->skill->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, Applications>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Applications $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setCandidate($this);
        }

        return $this;
    }

    public function removeApplication(Applications $application): self
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getCandidate() === $this) {
                $application->setCandidate(null);
            }
        }

        return $this;
    }

     public function getCountry(): ?Country
   {
       return $this->country;
   }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

 /**
 * Get the full username if first and last name are set,
 * the username otherwise
 *
 * @return string
 */
public function getfull(): string
{
    if ($this->firstname && $this->lastname) {
        return $this->firstname . ' ' . $this->lastname;
    }

    return $this->Full;
}

public function getFullname(): ?string
{
    return $this->fullname;
}

public function setFullname(string $fullname): self
{
    $this->fullname = $fullname;

    return $this;
}
  public function __toString() 
         
         
             {
           return $this->fname ;
         
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

  public function getSeen(): ?string
  {
      return $this->seen;
  }

  public function setSeen(?string $seen): self
  {
      $this->seen = $seen;

      return $this;
  }

  public function getCv(): ?string
  {
      return $this->cv;
  }

  public function setCv(?string $cv): self
  {
      $this->cv = $cv;

      return $this;
  }

 

 




}

