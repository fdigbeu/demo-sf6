<?php

namespace App\Entity;

use App\Repository\JobRepository;
use App\Traits\TimeStampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    use TimeStampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $designation = null;

    #[ORM\OneToMany(mappedBy: 'job', targetEntity: InfosUser::class)]
    private Collection $infosUsers;

    public function __construct()
    {
        $this->infosUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * @return Collection<int, InfosUser>
     */
    public function getInfosUsers(): Collection
    {
        return $this->infosUsers;
    }

    public function addInfosUser(InfosUser $infosUser): static
    {
        if (!$this->infosUsers->contains($infosUser)) {
            $this->infosUsers->add($infosUser);
            $infosUser->setJob($this);
        }

        return $this;
    }

    public function removeInfosUser(InfosUser $infosUser): static
    {
        if ($this->infosUsers->removeElement($infosUser)) {
            // set the owning side to null (unless already changed)
            if ($infosUser->getJob() === $this) {
                $infosUser->setJob(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->designation;
    }
}
