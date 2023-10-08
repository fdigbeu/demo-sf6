<?php

namespace App\Entity;

use App\Repository\InfosUserRepository;
use App\Traits\TimeStampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: InfosUserRepository::class)]
class InfosUser
{
    use TimeStampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    #[Assert\Length(
        min: 1,
        max: 200,
        minMessage: 'La valeur minimale est de {{ limit }}',
        maxMessage: 'La valeur maximale est de {{ limit }}',
    )]
    private ?int $age = null;

    #[ORM\OneToOne(inversedBy: 'infosUser', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    #[ORM\ManyToOne(inversedBy: 'infosUsers')]
    private ?Job $job = null;

    #[ORM\ManyToMany(targetEntity: Hobby::class, inversedBy: 'infosUsers')]
    private Collection $hobbies;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $photo = null;

    #[ORM\OneToOne(mappedBy: 'infosUser', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->hobbies = new ArrayCollection();
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): static
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Collection<int, Hobby>
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobby $hobby): static
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies->add($hobby);
        }

        return $this;
    }

    public function removeHobby(Hobby $hobby): static
    {
        $this->hobbies->removeElement($hobby);

        return $this;
    }

    public function __toString(){
        return $this->lastname." ".$this->firstname;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setInfosUser(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getInfosUser() !== $this) {
            $user->setInfosUser($this);
        }

        $this->user = $user;

        return $this;
    }
}
