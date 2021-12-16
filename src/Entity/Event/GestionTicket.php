<?php

namespace App\Entity\Event;

use App\Entity\Home\GuestUser;
use App\Entity\Home\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Event\GestionTicketRepository")
 * @ORM\Table(name="kossa_event_gestion_ticket")
 */
class GestionTicket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Home\User", inversedBy="ticketsAchetes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Home\GuestUser", inversedBy="ticketsAchetes")
     */
    private $guest;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event\Ticket", inversedBy="gestionTickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre=1;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix=0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    
     public function __construct()
    {
        $this->date = new \DateTime();
     }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGuest(): ?GuestUser
    {
        return $this->guest;
    }

    public function setGuest(?GuestUser $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

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
}
