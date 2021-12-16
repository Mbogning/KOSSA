<?php

namespace App\Entity\Movie;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Movie\SerieRepository")
 * @ORM\Table(name="kossa_movie_serie")
 */
class Serie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $fichier;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $extrait;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Home\User", mappedBy="serie_artiste")
     */
    private $artistes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Home\User", mappedBy="serie_realisateur")
     */
    private $realisateurs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="series_producteur")
     */
    private $producteurs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie\GenreVideo", inversedBy="series_video")
     */
    private $genresVideos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movie\Episode", mappedBy="serie")
     */
    private $episodes;

   
    public function __construct()
    {
        $this->artistes = new ArrayCollection();
        $this->realisateurs = new ArrayCollection();
        $this->producteurs = new ArrayCollection();
        $this->genresVideos = new ArrayCollection();
        $this->episodes = new ArrayCollection();
        $this->users_serie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * @return Collection|User[]
     */
    public function getArtistes(): Collection
    {
        return $this->artistes;
    }

    public function addArtiste(User $artiste): self
    {
        if (!$this->artistes->contains($artiste)) {
            $this->artistes[] = $artiste;
            $artiste->setSerieArtiste($this);
        }

        return $this;
    }

    public function removeArtiste(User $artiste): self
    {
        if ($this->artistes->contains($artiste)) {
            $this->artistes->removeElement($artiste);
            // set the owning side to null (unless already changed)
            if ($artiste->getSerieArtiste() === $this) {
                $artiste->setSerieArtiste(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRealisateurs(): Collection
    {
        return $this->realisateurs;
    }

    public function addRealisateur(User $realisateur): self
    {
        if (!$this->realisateurs->contains($realisateur)) {
            $this->realisateurs[] = $realisateur;
            $realisateur->setSerieRealisateur($this);
        }

        return $this;
    }

    public function removeRealisateur(User $realisateur): self
    {
        if ($this->realisateurs->contains($realisateur)) {
            $this->realisateurs->removeElement($realisateur);
            // set the owning side to null (unless already changed)
            if ($realisateur->getSerieRealisateur() === $this) {
                $realisateur->setSerieRealisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getProducteurs(): Collection
    {
        return $this->producteurs;
    }

    public function addProducteur(User $producteur): self
    {
        if (!$this->producteurs->contains($producteur)) {
            $this->producteurs[] = $producteur;
        }

        return $this;
    }

    public function removeProducteur(User $producteur): self
    {
        if ($this->producteurs->contains($producteur)) {
            $this->producteurs->removeElement($producteur);
        }

        return $this;
    }

    /**
     * @return Collection|GenreVideo[]
     */
    public function getGenresVideos(): Collection
    {
        return $this->genresVideos;
    }

    public function addGenresVideo(GenreVideo $genresVideo): self
    {
        if (!$this->genresVideos->contains($genresVideo)) {
            $this->genresVideos[] = $genresVideo;
        }

        return $this;
    }

    public function removeGenresVideo(GenreVideo $genresVideo): self
    {
        if ($this->genresVideos->contains($genresVideo)) {
            $this->genresVideos->removeElement($genresVideo);
        }

        return $this;
    }

    /**
     * @return Collection|Episode[]
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
            $episode->setSerie($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        if ($this->episodes->contains($episode)) {
            $this->episodes->removeElement($episode);
            // set the owning side to null (unless already changed)
            if ($episode->getSerie() === $this) {
                $episode->setSerie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersSerie(): Collection
    {
        return $this->users_serie;
    }

   
}
