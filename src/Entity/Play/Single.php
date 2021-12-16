<?php

namespace App\Entity\Play;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\User;
use App\Entity\News\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Play\SingleRepository")
 * @ORM\Table(name="kossa_play_single")
 */
class Single
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
    private $titre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $noPiste;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\GenreMusical", inversedBy="singles")
     */
    private $genresMorceaux;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Home\User", inversedBy="singles")
     */
    private $artiste;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="singles")
     */
    private $featuring;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vues;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $fichier;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $extrait;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Play\Video", mappedBy="single")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News\Article", mappedBy="single")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $annee;

    public function __construct()
    {
        $this->genresMorceaux = new ArrayCollection();
        $this->featuring = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->users_single = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNoPiste(): ?int
    {
        return $this->noPiste;
    }

    public function setNoPiste(?int $noPiste): self
    {
        $this->noPiste = $noPiste;

        return $this;
    }

    /**
     * @return Collection|GenreMusical[]
     */
    public function getGenresMorceaux(): Collection
    {
        return $this->genresMorceaux;
    }

    public function addGenresMorceaux(GenreMusical $genresMorceaux): self
    {
        if (!$this->genresMorceaux->contains($genresMorceaux)) {
            $this->genresMorceaux[] = $genresMorceaux;
        }

        return $this;
    }

    public function removeGenresMorceaux(GenreMusical $genresMorceaux): self
    {
        if ($this->genresMorceaux->contains($genresMorceaux)) {
            $this->genresMorceaux->removeElement($genresMorceaux);
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

    /**
     * @return Collection|User[]
     */
    public function getFeaturing(): Collection
    {
        return $this->featuring;
    }

    public function addFeaturing(User $featuring): self
    {
        if (!$this->featuring->contains($featuring)) {
            $this->featuring[] = $featuring;
        }

        return $this;
    }

    public function removeFeaturing(User $featuring): self
    {
        if ($this->featuring->contains($featuring)) {
            $this->featuring->removeElement($featuring);
        }

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

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

    public function getFichier(): ?Media
    {
        return $this->fichier;
    }

    public function setFichier(?Media $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getExtrait(): ?Media
    {
        return $this->extrait;
    }

    public function setExtrait(?Media $extrait): self
    {
        $this->extrait = $extrait;

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
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setSingle($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getSingle() === $this) {
                $video->setSingle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setSingle($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getSingle() === $this) {
                $article->setSingle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersSingle(): Collection
    {
        return $this->users_single;
    }
    
     public function __toString() {
        return $this->titre ? $this->titre : '';
    }

     public function getDuree(): ?int
     {
         return $this->duree;
     }

     public function setDuree(?int $duree): self
     {
         $this->duree = $duree;

         return $this;
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

     public function getAnnee(): ?int
     {
         return $this->annee;
     }

     public function setAnnee(?int $annee): self
     {
         $this->annee = $annee;

         return $this;
     }

}
