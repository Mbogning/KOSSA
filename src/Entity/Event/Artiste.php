<?php

namespace App\Entity\Event;

use App\Entity\Home\GuestUser;
use App\Entity\Home\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Event\ArtisteRepository")
 * @ORM\Table(name="kossa_event_artiste")
 */
class Artiste
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre=0;
    
    private $hasvoted=false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="award_artistes")
     * @ORM\JoinTable(name="kossa_event_artiste_user_vote")
     *      */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\GuestUser", inversedBy="award_artistes")
     * @ORM\JoinTable(name="kossa_event_artiste_guest_vote")
     */
    private $guests;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Home\User", inversedBy="award_artistes_nomines")
     */
    private $artiste;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event\CategorieAward", inversedBy="artistes")
     */
    private $categorieAward;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->guests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    
     public function getHasvoted()
    {
        return $this->hasvoted;
    }

    public function setHasvoted($value): self
    {
        $this->hasvoted = $value;

        return $this;
    }

    
    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|GuestUser[]
     */
    public function getGuests(): Collection
    {
        return $this->guests;
    }

    public function addGuest(GuestUser $guest): self
    {
        if (!$this->guests->contains($guest)) {
            $this->guests[] = $guest;
        }

        return $this;
    }

    public function removeGuest(GuestUser $guest): self
    {
        if ($this->guests->contains($guest)) {
            $this->guests->removeElement($guest);
        }

        return $this;
    }

    public function getArtiste(): ?User
    {
        return $this->artiste;
    }

    public function setArtiste(?User $artiste): self
    {
        $this->artiste = $artiste;

        return $this;
    }

    public function getCategorieAward(): ?CategorieAward
    {
        return $this->categorieAward;
    }

    public function setCategorieAward(?CategorieAward $categorieAward): self
    {
        $this->categorieAward = $categorieAward;

        return $this;
    }
    
    public function __toString() {
        return $this->id ? $this->id."" : '';
    }
}
