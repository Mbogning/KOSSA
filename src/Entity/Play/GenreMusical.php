<?php

namespace App\Entity\Play;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\News\Comment;
use App\Entity\Home\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Play\GenreMusicalRepository")
 * @ORM\Table(name="kossa_play_genre_musical")
 */
class GenreMusical
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
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vues=0;

    /**
     * @Exclude
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", mappedBy="genresMusicaux")
     */
    private $users;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\Morceau", mappedBy="genresMusicaux")
     */
    private $morceaus;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\Album", mappedBy="genresMusicaux")
     */
    private $albums;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\Single", mappedBy="genresMorceaux")
     */
    private $singles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="App\Entity\Play\TypeMusical", inversedBy="genreMusicals")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $jaime=0;

    /**
     * @ORM\Column(type="integer")
     */
    private $jaimepas=0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News\Comment",
     *  mappedBy="genreMusical",
     *  orphanRemoval=true,
     *  cascade={"persist"}
     * )
     * @ORM\OrderBy({"publishedAt": "DESC"})
     */
    private $comments;

    /**
     * 
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="favorisGenreMusicals")
     * @ORM\JoinTable(name="kossa_news_genre_musical_user_favoris")
     */
    private $user_favoris;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="jaimeGenreMusicals")
     * @ORM\JoinTable(name="kossa_news_genre_musical_user_jaime")
     */
    private $user_jaime;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="jaimepasGenreMuscials")
     * @ORM\JoinTable(name="kossa_news_genre_musical_user_jaimepas")
     */
    private $user_jaimepas;
    
    private $photoUrl;
    
    private $link;
    

    
     public function __toString() {
        return $this->nom ? $this->nom : '';
    }
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->morceaus = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->singles = new ArrayCollection();
        $this->users_genre_musicaux = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->user_favoris = new ArrayCollection();
        $this->user_jaime = new ArrayCollection();
        $this->user_jaimepas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getVues(): ?int
    {
        return $this->vues;
    }

    public function setVues(?int $vues): self
    {
        $this->vues = $vues;

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
            $user->addGenresMusicaux($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeGenresMusicaux($this);
        }

        return $this;
    }

    /**
     * @return Collection|Morceau[]
     */
    public function getMorceaus(): Collection
    {
        return $this->morceaus;
    }

    public function addMorceau(Morceau $morceau): self
    {
        if (!$this->morceaus->contains($morceau)) {
            $this->morceaus[] = $morceau;
            $morceau->addGenresMusicaux($this);
        }

        return $this;
    }

    public function removeMorceau(Morceau $morceau): self
    {
        if ($this->morceaus->contains($morceau)) {
            $this->morceaus->removeElement($morceau);
            $morceau->removeGenresMusicaux($this);
        }

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->addGenresMusicaux($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            $album->removeGenresMusicaux($this);
        }

        return $this;
    }

    /**
     * @return Collection|Single[]
     */
    public function getSingles(): Collection
    {
        return $this->singles;
    }

    public function addSingle(Single $single): self
    {
        if (!$this->singles->contains($single)) {
            $this->singles[] = $single;
            $single->addGenresMorceaux($this);
        }

        return $this;
    }

    public function removeSingle(Single $single): self
    {
        if ($this->singles->contains($single)) {
            $this->singles->removeElement($single);
            $single->removeGenresMorceaux($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersGenreMusicaux(): Collection
    {
        return $this->users_genre_musicaux;
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

    public function getType(): ?TypeMusical
    {
        return $this->type;
    }

    public function setType(?TypeMusical $type): self
    {
        $this->type = $type;

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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setGenreMusical($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getGenreMusical() === $this) {
                $comment->setGenreMusical(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserFavoris(): Collection
    {
        return $this->user_favoris;
    }

    public function addUserFavori(User $userFavori): self
    {
        if (!$this->user_favoris->contains($userFavori)) {
            $this->user_favoris[] = $userFavori;
        }

        return $this;
    }

    public function removeUserFavori(User $userFavori): self
    {
        if ($this->user_favoris->contains($userFavori)) {
            $this->user_favoris->removeElement($userFavori);
        }

        return $this;
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

   

}
