<?php

namespace App\Entity\Movie;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Movie\FilmRepository")
 * @ORM\Table(name="kossa_movie_film")
 */
class Film
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
    private $dateSortie;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Home\User", mappedBy="film")
     */
    private $producteurs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie\GenreVideo", inversedBy="films")
     */
    private $genresVideos;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $fichier;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $extrait;

    public function __construct()
    {
        $this->producteurs = new ArrayCollection();
        $this->genresVideos = new ArrayCollection();
        $this->users_film = new ArrayCollection();
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

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(?\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

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
            $producteur->setFilm($this);
        }

        return $this;
    }

    public function removeProducteur(User $producteur): self
    {
        if ($this->producteurs->contains($producteur)) {
            $this->producteurs->removeElement($producteur);
            // set the owning side to null (unless already changed)
            if ($producteur->getFilm() === $this) {
                $producteur->setFilm(null);
            }
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
    public function getUsersFilm(): Collection
    {
        return $this->users_film;
    }


}
