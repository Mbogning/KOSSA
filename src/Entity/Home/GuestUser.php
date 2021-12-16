<?php

namespace App\Entity\Home;

use App\Entity\Event\Artiste;
use App\Entity\Event\GestionTicket;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Home\GuestUserRepository")
 * @ORM\Table(name="kossa_home_guest_user")
 */
class GuestUser
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event\Artiste", mappedBy="guests")
     */
    private $award_artistes;

   
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event\GestionTicket", mappedBy="guest")
     */
    private $ticketsAchetes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    public function __construct()
    {
        $this->award_artistes = new ArrayCollection();
        $this->ticketsAchetes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Artiste[]
     */
    public function getAwardArtistes(): Collection
    {
        return $this->award_artistes;
    }

    public function addAwardArtiste(Artiste $awardArtiste): self
    {
        if (!$this->award_artistes->contains($awardArtiste)) {
            $this->award_artistes[] = $awardArtiste;
            $awardArtiste->addGuest($this);
        }

        return $this;
    }

    public function removeAwardArtiste(Artiste $awardArtiste): self
    {
        if ($this->award_artistes->contains($awardArtiste)) {
            $this->award_artistes->removeElement($awardArtiste);
            $awardArtiste->removeGuest($this);
        }

        return $this;
    }

    /**
     * @return Collection|GestionTicket[]
     */
    public function getTicketsAchetes(): Collection
    {
        return $this->ticketsAchetes;
    }

    public function addTicketsAchete(GestionTicket $ticketsAchete): self
    {
        if (!$this->ticketsAchetes->contains($ticketsAchete)) {
            $this->ticketsAchetes[] = $ticketsAchete;
            $ticketsAchete->setGuest($this);
        }

        return $this;
    }

    public function removeTicketsAchete(GestionTicket $ticketsAchete): self
    {
        if ($this->ticketsAchetes->contains($ticketsAchete)) {
            $this->ticketsAchetes->removeElement($ticketsAchete);
            // set the owning side to null (unless already changed)
            if ($ticketsAchete->getGuest() === $this) {
                $ticketsAchete->setGuest(null);
            }
        }

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }
}
