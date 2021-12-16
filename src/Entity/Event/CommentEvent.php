<?php

namespace App\Entity\Event;

use App\Entity\Home\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Event\CommentEventRepository")
 * @ORM\Table(name="kossa_event_comment")
 */
class CommentEvent
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
 * @ORM\Column(type="text")
 * @Assert\NotBlank(message="comment.blank")
 * @Assert\Length(
 *     min=5,
 *     minMessage="comment.too_short",
 *     max=10000,
 *     maxMessage="comment.too_long"
 * )
 */
private $content;
private $authorPhotoUrl;

/**
 * @var \DateTime
 *
 * @ORM\Column(type="datetime")
 */
private $publishedAt;

/**
 * @var User
 *
 * @ORM\ManyToOne(targetEntity="App\Entity\Home\User")
 * @ORM\JoinColumn(nullable=false)
 */
private $author;

/**
 * @ORM\ManyToOne(targetEntity="App\Entity\Event\Event", inversedBy="comments")
 */
private $event;

public function __construct()
{
$this->publishedAt = new \DateTime();
}

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->id ? $this->id."" : '';
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

     public function getAuthor(): ?User
     {
         return $this->author;
     }

     public function setAuthor(?User $author): self
     {
         $this->author = $author;

         return $this;
     }
     
     public function getAuthorPhotoUrl()
{
return $this->authorPhotoUrl;
}

public function setAuthorPhotoUrl($authorPhotoUrl): self
{
$this->authorPhotoUrl = $authorPhotoUrl;

return $this;
}

}
