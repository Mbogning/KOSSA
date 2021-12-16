<?php

namespace App\Entity\Play;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\User;
use App\Entity\News\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Play\VideoRepository")
 */
class Video
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
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $fichier;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $lien;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Home\User", inversedBy="videos")
     */
    private $artiste;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Play\Single", inversedBy="videos")
     */
    private $single;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Play\Morceau", inversedBy="videos")
     */
    private $morceau;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News\Article", mappedBy="video")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->users_video = new ArrayCollection();
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

    public function getPhoto(): ?Media
    {
        return $this->photo;
    }

    public function setPhoto(?Media $photo): self
    {
        $this->photo = $photo;

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

    public function getLien(): ?Media
    {
        return $this->lien;
    }

    public function setLien(?Media $lien): self
    {
        $this->lien = $lien;

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

    public function getSingle(): ?Single
    {
        return $this->single;
    }

    public function setSingle(?Single $single): self
    {
        $this->single = $single;

        return $this;
    }

    public function getMorceau(): ?Morceau
    {
        return $this->morceau;
    }

    public function setMorceau(?Morceau $morceau): self
    {
        $this->morceau = $morceau;

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
            $article->setVideo($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getVideo() === $this) {
                $article->setVideo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersVideo(): Collection
    {
        return $this->users_video;
    }

   
}
