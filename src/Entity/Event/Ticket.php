<?php

namespace App\Entity\Event;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Event\TicketRepository")
 * @ORM\Table(name="kossa_event_ticket")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez remplir le type")
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prix=0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reste;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event\Event", inversedBy="tickets")
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event\GestionTicket", mappedBy="ticket")
     */
    private $gestionTickets;

     public function __construct()
    {
        $this->date = new \DateTime();
        $this->active = true;
        $this->gestionTickets = new ArrayCollection();
     }
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getReste(): ?int
    {
        return $this->reste;
    }

    public function setReste(?int $reste): self
    {
        $this->reste = $reste;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
    
     public function __toString() {
        return $this->type ? $this->type."" : '';
    }

     /**
      * @return Collection|GestionTicket[]
      */
     public function getGestionTickets(): Collection
     {
         return $this->gestionTickets;
     }

     public function addGestionTicket(GestionTicket $gestionTicket): self
     {
         if (!$this->gestionTickets->contains($gestionTicket)) {
             $this->gestionTickets[] = $gestionTicket;
             $gestionTicket->setTicket($this);
         }

         return $this;
     }

     public function removeGestionTicket(GestionTicket $gestionTicket): self
     {
         if ($this->gestionTickets->contains($gestionTicket)) {
             $this->gestionTickets->removeElement($gestionTicket);
             // set the owning side to null (unless already changed)
             if ($gestionTicket->getTicket() === $this) {
                 $gestionTicket->setTicket(null);
             }
         }

         return $this;
     }
}
