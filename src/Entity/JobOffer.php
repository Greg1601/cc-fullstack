<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobOfferRepository")
 */
class JobOffer
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
    private $jobName;

    /**
     * @ORM\Column(type="text")
     */
    private $jobDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $requiredExperience;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jobPlace;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

    /**
     * @ORM\Column(type="boolean")
     */
    private $remotePossibility;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $isChecked;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $jobSheet;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visibility;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFilled;

    public function __construct()
    {
        $this->visibility = true;
        $this->isFilled = false;
        $this->isChecked = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobName(): ?string
    {
        return $this->jobName;
    }

    public function setJobName(string $jobName): self
    {
        $this->jobName = $jobName;

        return $this;
    }

    public function getJobDescription(): ?string
    {
        return $this->jobDescription;
    }

    public function setJobDescription(string $jobDescription): self
    {
        $this->jobDescription = $jobDescription;

        return $this;
    }

    public function getRequiredExperience(): ?string
    {
        return $this->requiredExperience;
    }

    public function setRequiredExperience(string $requiredExperience): self
    {
        $this->requiredExperience = $requiredExperience;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getJobPlace(): ?string
    {
        return $this->jobPlace;
    }

    public function setJobPlace(string $jobPlace): self
    {
        $this->jobPlace = $jobPlace;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getRemotePossibility(): ?bool
    {
        return $this->remotePossibility;
    }

    public function setRemotePossibility(bool $remotePossibility): self
    {
        $this->remotePossibility = $remotePossibility;

        return $this;
    }

    public function getCompany(): ?company
    {
        return $this->company;
    }

    public function setCompany(?company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getIsChecked(): ?bool
    {
        return $this->isChecked;
    }

    public function setIsChecked(bool $isChecked): self
    {
        $this->isChecked = $isChecked;

        return $this;
    }

    public function __toString(){
        return $this->name;
    }

    public function getJobSheet(): ?string
    {
        return $this->jobSheet;
    }

    public function setJobSheet(?string $jobSheet): self
    {
        $this->jobSheet = $jobSheet;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getIsFilled(): ?bool
    {
        return $this->isFilled;
    }

    public function setIsFilled(bool $isFilled): self
    {
        $this->isFilled = $isFilled;

        return $this;
    }

}
