<?php

namespace App\Entity\Event;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\Tag;
use App\Entity\Home\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Event\EventRepository")
 * @ORM\Table(name="kossa_event_event")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="post.blank_content")
     * @Assert\Length(min=10, minMessage="post.too_short_content")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $publishedAt;
    
     /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $photo;
    
    private $photoUrl;
    private $link;
    
     /**
     * @ORM\Column(type="integer")
     */
    private $vues=0;
     /**
     * @ORM\Column(type="integer")
     */
    private $jaime=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $jaimepas=0;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRedaction;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="events_user_jaime")
     * @ORM\JoinTable(name="kossa_event_event_user_jaime")
     */
    private $user_jaime;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="events_user_jaimepas")
     * @ORM\JoinTable(name="kossa_event_event_user_jaimepas")
     */
    private $user_jaimepas;

    /**
     * @ORM\OneToMany(
     * targetEntity="App\Entity\Event\CommentEvent",
     * mappedBy="event",
     * orphanRemoval=true,
     * cascade={"persist"})
     * @ORM\OrderBy({"publishedAt": "DESC"})
     * )
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Home\User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var Tag[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\Tag", cascade={"persist"})
     * @ORM\JoinTable(name="kossa_event_event_tag")
     * @ORM\OrderBy({"name": "ASC"})
     * @Assert\Count(max="4", maxMessage="post.too_many_tags")
     */
    private $tags;

 
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\OneToMany(
     * targetEntity="App\Entity\Event\Ticket",
     * mappedBy="event",
     * cascade={"persist", "remove"})
     * @ORM\OrderBy({"prix": "ASC"})
     * )
     */
    private $tickets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event\CategorieAward", mappedBy="event",cascade={"persist", "remove"})
     */
    private $categorieAwards;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event\CategorieEvent", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorieEvent;
    
    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $publie;
    
     /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFinNomines;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $contentFormatter;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
     private $rawContent;

   
    public function __construct()
    {
        $this->dateRedaction = new \DateTime();
        $this->publishedAt = new \DateTime();
       $this->user_jaime = new ArrayCollection();
        $this->user_jaimepas = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->categorieAwards = new ArrayCollection();
    }
    
    
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserJaime(): Collection
    {
        return $this->user_jaime;
    }

    public function addUserJaime(User $userJaime): self
    {
        if (!$this->user_jaime->contains($userJaime)) {
            $this->user_jaime[] = $userJaime;
        }

        return $this;
    }

    public function removeUserJaime(User $userJaime): self
    {
        if ($this->user_jaime->contains($userJaime)) {
            $this->user_jaime->removeElement($userJaime);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserJaimepas(): Collection
    {
        return $this->user_jaimepas;
    }

    public function addUserJaimepa(User $userJaimepa): self
    {
        if (!$this->user_jaimepas->contains($userJaimepa)) {
            $this->user_jaimepas[] = $userJaimepa;
        }

        return $this;
    }

    public function removeUserJaimepa(User $userJaimepa): self
    {
        if ($this->user_jaimepas->contains($userJaimepa)) {
            $this->user_jaimepas->removeElement($userJaimepa);
        }

        return $this;
    }

    /**
     * @return Collection|CommentEvent[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(CommentEvent $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(CommentEvent $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getEvent() === $this) {
                $comment->setEvent(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getEvent() === $this) {
                $ticket->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CategorieAward[]
     */
    public function getCategorieAwards(): Collection
    {
        return $this->categorieAwards;
    }

    public function addCategorieAward(CategorieAward $categorieAward): self
    {
        if (!$this->categorieAwards->contains($categorieAward)) {
            $this->categorieAwards[] = $categorieAward;
            $categorieAward->setEvent($this);
        }

        return $this;
    }

    public function removeCategorieAward(CategorieAward $categorieAward): self
    {
        if ($this->categorieAwards->contains($categorieAward)) {
            $this->categorieAwards->removeElement($categorieAward);
            // set the owning side to null (unless already changed)
            if ($categorieAward->getEvent() === $this) {
                $categorieAward->setEvent(null);
            }
        }

        return $this;
    }

    public function getCategorieEvent(): ?CategorieEvent
    {
        return $this->categorieEvent;
    }

    public function setCategorieEvent(?CategorieEvent $categorieEvent): self
    {
        $this->categorieEvent = $categorieEvent;

        return $this;
    }

    
    
     public function __toString() {
        return $this->titre ? $this->titre."" : '';
    }

     public function getContent(): ?string
     {
         return $this->content;
     }

     public function setContent(string $content): self
     {
         $this->content = $content;

         return $this;
     }

     public function getPublishedAt(): ?\DateTimeInterface
     {
         return $this->publishedAt;
     }

     public function setPublishedAt(\DateTimeInterface $publishedAt): self
     {
         $this->publishedAt = $publishedAt;

         return $this;
     }

     public function getVues(): ?int
     {
         return $this->vues;
     }

     public function setVues(int $vues): self
     {
         $this->vues = $vues;

         return $this;
     }

     public function getJaime(): ?int
     {
         return $this->jaime;
     }

     public function setJaime(int $jaime): self
     {
         $this->jaime = $jaime;

         return $this;
     }

     public function getJaimepas(): ?int
     {
         return $this->jaimepas;
     }

     public function setJaimepas(int $jaimepas): self
     {
         $this->jaimepas = $jaimepas;

         return $this;
     }

     public function getDateRedaction(): ?\DateTimeInterface
     {
         return $this->dateRedaction;
     }

     public function setDateRedaction(?\DateTimeInterface $dateRedaction): self
     {
         $this->dateRedaction = $dateRedaction;

         return $this;
     }

     public function getPhoto(): ?Media
     {
         return $this->photo;
     }

     public function setPhoto(?Media $photo): self
     {
         $this->photo = $photo;

         return $this;
     }

     public function getPublie(): ?bool
     {
         return $this->publie;
     }

     public function setPublie(bool $publie): self
     {
         $this->publie = $publie;

         return $this;
     }
     
        public function getPhotoUrl()
{
return $this->photoUrl;
}

public function setPhotoUrl($photoUrl): self
{
$this->photoUrl = $photoUrl;

return $this;
}

public function getLink()
{
return $this->link;
}

public function setLink($link): self
{
$this->link = $link;

return $this;
}

public function getSlug(): ?string
{
    return $this->slug;
}

public function setSlug(string $slug): self
{
    $this->slug = $slug;

    return $this;
}

public function getDateFinNomines(): ?\DateTimeInterface
{
    return $this->dateFinNomines;
}

public function setDateFinNomines(?\DateTimeInterface $dateFinNomines): self
{
    $this->dateFinNomines = $dateFinNomines;

    return $this;
}

public function getContentFormatter(): ?string
{
    return $this->contentFormatter;
}

public function setContentFormatter(?string $contentFormatter): self
{
    $this->contentFormatter = $contentFormatter;

    return $this;
}

public function getRawContent(): ?string
{
    return $this->rawContent;
}

public function setRawContent(?string $rawContent): self
{
    $this->rawContent = $rawContent;

    return $this;
}

}
