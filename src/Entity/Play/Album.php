<?php

namespace App\Entity\Play;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Play\AlbumRepository")
 * @ORM\Table(name="kossa_play_album")
 */
class Album
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
    private $nbrePiste;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vues;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Home\User", inversedBy="albums")
     */
    private $artiste;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\GenreMusical", inversedBy="albums")
     */
    private $genresMusicaux;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $cover1;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $cover2;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Play\Morceau", mappedBy="album")
     */
    private $morceaus;

   
    public function __construct()
    {
        $this->genresMusicaux = new ArrayCollection();
        $this->morceaus = new ArrayCollection();
        $this->users_album = new ArrayCollection();
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

    public function getNbrePiste(): ?int
    {
        return $this->nbrePiste;
    }

    public function setNbrePiste(?int $nbrePiste): self
    {
        $this->nbrePiste = $nbrePiste;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
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

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getCover1(): ?Media
    {
        return $this->cover1;
    }

    public function setCover1(?Media $cover1): self
    {
        $this->cover1 = $cover1;

        return $this;
    }

    public function getCover2(): ?Media
    {
        return $this->cover2;
    }

    public function setCover2(?Media $cover2): self
    {
        $this->cover2 = $cover2;

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
            $morceau->setAlbum($this);
        }

        return $this;
    }

    public function removeMorceau(Morceau $morceau): self
    {
        if ($this->morceaus->contains($morceau)) {
            $this->morceaus->removeElement($morceau);
            // set the owning side to null (unless already changed)
            if ($morceau->getAlbum() === $this) {
                $morceau->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersAlbum(): Collection
    {
        return $this->users_album;
    }

  
}
