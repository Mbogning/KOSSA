<?php

namespace App\Entity\Play;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Play\TypeMusicalRepository")
 * @ORM\Table(name="kossa_play_type_musical")
 */
class TypeMusical
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
     * @ORM\OneToMany(targetEntity="App\Entity\Play\GenreMusical", mappedBy="type")
     * @ORM\OrderBy({"nom": "ASC"})
     */
    private $genreMusicals;

    public function __construct()
    {
        $this->genreMusicals = new ArrayCollection();
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

    /**
     * @return Collection|GenreMusical[]
     */
    public function getGenreMusicals(): Collection
    {
        return $this->genreMusicals;
    }

    public function addGenreMusical(GenreMusical $genreMusical): self
    {
        if (!$this->genreMusicals->contains($genreMusical)) {
            $this->genreMusicals[] = $genreMusical;
            $genreMusical->setType($this);
        }

        return $this;
    }

    public function removeGenreMusical(GenreMusical $genreMusical): self
    {
        if ($this->genreMusicals->contains($genreMusical)) {
            $this->genreMusicals->removeElement($genreMusical);
            // set the owning side to null (unless already changed)
            if ($genreMusical->getType() === $this) {
                $genreMusical->setType(null);
            }
        }

        return $this;
    }
    
     public function __toString() {
        return $this->nom ? $this->nom : '';
    }
}
