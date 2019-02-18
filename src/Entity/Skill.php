<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SkillRepository")
 */
class Skill
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Talent", mappedBy="skills")
     */
    private $talents;

    public function __construct()
    {
        $this->talents = new ArrayCollection();
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

    /**
     * @return Collection|Talent[]
     */
    public function getTalents(): Collection
    {
        return $this->talents;
    }

    public function addTalent(Talent $talent): self
    {
        if (!$this->talents->contains($talent)) {
            $this->talents[] = $talent;
            $talent->addSkill($this);
        }

        return $this;
    }

    public function removeTalent(Talent $talent): self
    {
        if ($this->talents->contains($talent)) {
            $this->talents->removeElement($talent);
            $talent->removeSkill($this);
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }
}
