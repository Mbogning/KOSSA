<?php

namespace App\Entity\Play;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\User;
use App\Entity\News\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Play\MorceauRepository")
 * @ORM\Table(name="kossa_play_morceau")
 */
class Morceau
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
     * @ORM\Column(type="time", nullable=true)
     */
    private $duree;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\GenreMusical", inversedBy="morceaus")
     */
    private $genresMusicaux;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="morceaus")
     */
    private $featuring;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Play\Album", inversedBy="morceaus")
     */
    private $album;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Play\Video", mappedBy="morceau")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News\Article", mappedBy="morceau")
     */
    private $articles;

    
    public function __construct()
    {
        $this->genresMusicaux = new ArrayCollection();
        $this->featuring = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->users_morceau = new ArrayCollection();
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

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(?\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

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

    /**
     * @return Collection|GenreMusical[]
     */
    public function getGenresMusicaux(): Collection
    {
        return $this->genresMusicaux;
    }

    public function addGenresMusicaux(GenreMusical $genresMusicaux): self
    {
        if (!$this->genresMusicaux->contains($genresMusicaux)) {
            $this->genresMusicaux[] = $genresMusicaux;
        }

        return $this;
    }

    public function removeGenresMusicaux(GenreMusical $genresMusicaux): self
    {
        if ($this->genresMusicaux->contains($genresMusicaux)) {
            $this->genresMusicaux->removeElement($genresMusicaux);
        }

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

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

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
            $video->setMorceau($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getMorceau() === $this) {
                $video->setMorceau(null);
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
            $article->setMorceau($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getMorceau() === $this) {
                $article->setMorceau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersMorceau(): Collection
    {
        return $this->users_morceau;
    }

   

}
