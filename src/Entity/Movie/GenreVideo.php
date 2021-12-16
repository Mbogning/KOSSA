<?php

namespace App\Entity\Movie;

use App\Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Movie\GenreVideoRepository")
 * @ORM\Table(name="kossa_movie_genre_video")
 */
class GenreVideo
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vues;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie\Film", mappedBy="genresVideos")
     */
    private $films;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie\Serie", mappedBy="genresVideos")
     */
    private $series_video;

    public function __construct()
    {
        $this->films = new ArrayCollection();
        $this->series_video = new ArrayCollection();
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
     * @return Collection|Film[]
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function addFilm(Film $film): self
    {
        if (!$this->films->contains($film)) {
            $this->films[] = $film;
            $film->addGenresVideo($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): self
    {
        if ($this->films->contains($film)) {
            $this->films->removeElement($film);
            $film->removeGenresVideo($this);
        }

        return $this;
    }

    /**
     * @return Collection|Serie[]
     */
    public function getSeriesVideo(): Collection
    {
        return $this->series_video;
    }

    public function addSeriesVideo(Serie $seriesVideo): self
    {
        if (!$this->series_video->contains($seriesVideo)) {
            $this->series_video[] = $seriesVideo;
            $seriesVideo->addGenresVideo($this);
        }

        return $this;
    }

    public function removeSeriesVideo(Serie $seriesVideo): self
    {
        if ($this->series_video->contains($seriesVideo)) {
            $this->series_video->removeElement($seriesVideo);
            $seriesVideo->removeGenresVideo($this);
        }

        return $this;
    }
}
