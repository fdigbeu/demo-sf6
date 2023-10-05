<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use App\Traits\TimeStampTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    use TimeStampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $socialnetwork = null;

    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?InfosUser $infosUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getSocialnetwork(): ?string
    {
        return $this->socialnetwork;
    }

    public function setSocialnetwork(?string $socialnetwork): static
    {
        $this->socialnetwork = $socialnetwork;

        return $this;
    }

    public function getInfosUser(): ?InfosUser
    {
        return $this->infosUser;
    }

    public function setInfosUser(?InfosUser $infosUser): static
    {
        // unset the owning side of the relation if necessary
        if ($infosUser === null && $this->infosUser !== null) {
            $this->infosUser->setProfile(null);
        }

        // set the owning side of the relation if necessary
        if ($infosUser !== null && $infosUser->getProfile() !== $this) {
            $infosUser->setProfile($this);
        }

        $this->infosUser = $infosUser;

        return $this;
    }

    public function __toString(){
        return $this->socialnetwork." : ".$this->url;
    }
}
