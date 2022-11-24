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
     * 
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
     * @ORM\OneToMany(targetEntity=Cv::class, mappedBy="candidate")
     */
    private $cv;

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
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $about;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fb;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tw;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ln;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bdate;

   

    /**
     * @ORM\ManyToOne(targetEntity=Country::class,)
     */
    private $pcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cover;

    /**
     * @ORM\OneToMany(targetEntity=Formation::class, mappedBy="candidate")
     */
    private $formations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=WorkExperience::class, mappedBy="candidate")
     */
    private $workExperiences;

    /**
     * @ORM\OneToMany(targetEntity=Certificate::class, mappedBy="candidate")
     */
    private $certificates;

    /**
     * @ORM\OneToMany(targetEntity=Language::class, mappedBy="candidate")
     */
    private $languages;

   

  
   
 

    public function __construct()
    {
       
      
        $this->skill = new ArrayCollection();
        $this->cv = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->workExperiences = new ArrayCollection();
        $this->certificates = new ArrayCollection();
        $this->languages = new ArrayCollection();
      
       
      
       
    }

  

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return Collection<int, cv>
     */
    public function getcv(): Collection
    {
        return $this->cv;
    }

    public function addCv(Cv $cv): self
    {
        if (!$this->cv->contains($cv)) {
            $this->cv[] = $cv;
        }

        return $this;
    }

    public function removeCv(Cv $cv): self
    {
        $this->cv->removeElement($cv);

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

  

  public function getAbout(): ?string
  {
      return $this->about;
  }

  public function setAbout(?string $about): self
  {
      $this->about = $about;

      return $this;
  }

  public function getPhone(): ?string
  {
      return $this->phone;
  }

  public function setPhone(?string $phone): self
  {
      $this->phone = $phone;

      return $this;
  }

  public function getZip(): ?string
  {
      return $this->zip;
  }

  public function setZip(?string $zip): self
  {
      $this->zip = $zip;

      return $this;
  }

  public function getCity(): ?string
  {
      return $this->city;
  }

  public function setCity(?string $city): self
  {
      $this->city = $city;

      return $this;
  }

  public function getAddress(): ?string
  {
      return $this->address;
  }

  public function setAddress(?string $address): self
  {
      $this->address = $address;

      return $this;
  }

  public function getSite(): ?string
  {
      return $this->site;
  }

  public function setSite(?string $site): self
  {
      $this->site = $site;

      return $this;
  }

  public function getFb(): ?string
  {
      return $this->fb;
  }

  public function setFb(?string $fb): self
  {
      $this->fb = $fb;

      return $this;
  }

  public function getTw(): ?string
  {
      return $this->tw;
  }

  public function setTw(?string $tw): self
  {
      $this->tw = $tw;

      return $this;
  }

  public function getGl(): ?string
  {
      return $this->gl;
  }

  public function setGl(?string $gl): self
  {
      $this->gl = $gl;

      return $this;
  }

  public function getLn(): ?string
  {
      return $this->ln;
  }

  public function setLn(?string $ln): self
  {
      $this->ln = $ln;

      return $this;
  }

  public function getBdate(): ?string
  {
      return $this->bdate;
  }

  public function setBdate(?string $bdate): self
  {
      $this->bdate = $bdate;

      return $this;
  }

 

  public function getPcode(): ?Country
  {
      return $this->pcode;
  }

  public function setPcode(?Country $pcode): self
  {
      $this->pcode = $pcode;

      return $this;
  }

  public function getImage(): ?string
  {
      return $this->image;
  }

  public function setImage(?string $image): self
  {
      $this->image = $image;

      return $this;
  }

  public function getCover(): ?string
  {
      return $this->cover;
  }

  public function setCover(?string $cover): self
  {
      $this->cover = $cover;

      return $this;
  }

  /**
   * @return Collection<int, Formation>
   */
  public function getFormations(): Collection
  {
      return $this->formations;
  }

  public function addFormation(Formation $formation): self
  {
      if (!$this->formations->contains($formation)) {
          $this->formations[] = $formation;
          $formation->setCandidate($this);
      }

      return $this;
  }

  public function removeFormation(Formation $formation): self
  {
      if ($this->formations->removeElement($formation)) {
          // set the owning side to null (unless already changed)
          if ($formation->getCandidate() === $this) {
              $formation->setCandidate(null);
          }
      }

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

  /**
   * @return Collection<int, WorkExperience>
   */
  public function getWorkExperiences(): Collection
  {
      return $this->workExperiences;
  }

  public function addWorkExperience(WorkExperience $workExperience): self
  {
      if (!$this->workExperiences->contains($workExperience)) {
          $this->workExperiences[] = $workExperience;
          $workExperience->setCandidate($this);
      }

      return $this;
  }

  public function removeWorkExperience(WorkExperience $workExperience): self
  {
      if ($this->workExperiences->removeElement($workExperience)) {
          // set the owning side to null (unless already changed)
          if ($workExperience->getCandidate() === $this) {
              $workExperience->setCandidate(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, Certificate>
   */
  public function getCertificates(): Collection
  {
      return $this->certificates;
  }

  public function addCertificate(Certificate $certificate): self
  {
      if (!$this->certificates->contains($certificate)) {
          $this->certificates[] = $certificate;
          $certificate->setCandidate($this);
      }

      return $this;
  }

  public function removeCertificate(Certificate $certificate): self
  {
      if ($this->certificates->removeElement($certificate)) {
          // set the owning side to null (unless already changed)
          if ($certificate->getCandidate() === $this) {
              $certificate->setCandidate(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, Language>
   */
  public function getLanguages(): Collection
  {
      return $this->languages;
  }

  public function addLanguage(Language $language): self
  {
      if (!$this->languages->contains($language)) {
          $this->languages[] = $language;
          $language->setCandidate($this);
      }

      return $this;
  }

  public function removeLanguage(Language $language): self
  {
      if ($this->languages->removeElement($language)) {
          // set the owning side to null (unless already changed)
          if ($language->getCandidate() === $this) {
              $language->setCandidate(null);
          }
      }

      return $this;
  }

 

 

 




}

