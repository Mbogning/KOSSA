<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Home;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Event\Artiste;
use App\Entity\Event\Event;
use App\Entity\Event\GestionTicket;
use App\Entity\Movie\Film;
use App\Entity\Movie\Serie;
use App\Entity\News\Article;
use App\Entity\Play\Album;
use App\Entity\Play\GenreMusical;
use App\Entity\Play\Morceau;
use App\Entity\Play\Single;
use App\Entity\Play\Video;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Home\UserRepository")
 * @ORM\Table(name="kossa_home_user")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @Exclude
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     * @Assert\Email(message="l'email n'est pas un email valide")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @Exclude
     * @var array
     *
     * @ORM\Column(type="json")
     * @Assert\NotBlank(message="les droits sont obligatoires")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateNais;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anneeDeces;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $annee80;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vues;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;


    /**
     * @Exclude
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $videoPresentation;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\GenreMusical", inversedBy="users")
     * @Assert\NotBlank(message="veuillez ajouter au moins un genre musical")
     */
    private $genresMusicaux;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\Morceau", mappedBy="featuring")
     */
    private $morceaus;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="App\Entity\Play\Album", mappedBy="artiste")
     */
    private $albums;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="App\Entity\Play\Single", mappedBy="artiste")
     */
    private $singles;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="App\Entity\Play\Video", mappedBy="artiste")
     */
    private $videos;

    /**
     * @Exclude
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie\Serie", inversedBy="artistes")
     */
    private $serie_artiste;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie\Serie", inversedBy="realisateurs")
     */
    private $serie_realisateur;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie\Serie", mappedBy="producteurs")
     */
    private $series_producteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pseudo;

    /**
     * @Exclude
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     */
    private $photoCouverture;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="App\Entity\Home\MediaUnit", mappedBy="user_photo")
     */
    private $photos;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\GenreMusical", mappedBy="user_favoris")
     */
    private $favorisGenreMusicals;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\GenreMusical", mappedBy="user_jaime")
     */
    private $jaimeGenreMusicals;

    
    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Play\GenreMusical", mappedBy="user_jaimepas")
     */
    private $jaimepasGenreMuscials;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\News\Article", mappedBy="user_jaime")
     */
    private $articles_aime;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\News\Article", mappedBy="user_jaimepas")
     */
    private $articles_aimepas;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Event\Event", mappedBy="user_jaime")
     */
    private $events_user_jaime;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Event\Event", mappedBy="user_jaimepas")
     */
    private $events_user_jaimepas;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="App\Entity\Event\Event", mappedBy="author")
     */
    private $events;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="App\Entity\Event\Artiste", mappedBy="users")
     */
    private $award_artistes;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="App\Entity\Event\Artiste", mappedBy="artiste")
     */
    private $award_artistes_nomines;

    private $photoUrl;

    /**
     * @Exclude
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plainPassword;

    /**
     * @Exclude
     * @ORM\OneToMany(
     * targetEntity="App\Entity\Event\GestionTicket",
     * mappedBy="user")
     * @ORM\OrderBy({"date": "DESC"})
     * )
     */
    private $ticketsAchetes;

    public function __construct()
    {
        $this->genresMusicaux = new ArrayCollection();
        $this->morceaus = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->singles = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->series_producteur = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->favorisGenreMusicals = new ArrayCollection();
        $this->jaimeGenreMusicals = new ArrayCollection();
        $this->jaimepasGenreMuscials = new ArrayCollection();
        $this->articles_aime = new ArrayCollection();
        $this->articles_aimepas = new ArrayCollection();
        $this->events_user_jaime = new ArrayCollection();
        $this->events_user_jaimepas = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->award_artistes = new ArrayCollection();
        $this->award_artistes_nomines = new ArrayCollection();
        $this->ticketsAchetes = new ArrayCollection();
          
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getDateNais(): ?\DateTimeInterface
    {
        return $this->dateNais;
    }

    public function setDateNais(?\DateTimeInterface $dateNais): self
    {
        $this->dateNais = $dateNais;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

   
    public function getAnneeDeces(): ?int
    {
        return $this->anneeDeces;
    }

    public function setAnneeDeces(?int $anneeDeces): self
    {
        $this->anneeDeces = $anneeDeces;

        return $this;
    }

    public function getAnnee80(): ?bool
    {
        return $this->annee80;
    }

    public function setAnnee80(?bool $annee80): self
    {
        $this->annee80 = $annee80;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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


    public function getVideoPresentation(): ?Media
    {
        return $this->videoPresentation;
    }

    public function setVideoPresentation(?Media $videoPresentation): self
    {
        $this->videoPresentation = $videoPresentation;

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
            $morceau->addFeaturing($this);
        }

        return $this;
    }

    public function removeMorceau(Morceau $morceau): self
    {
        if ($this->morceaus->contains($morceau)) {
            $this->morceaus->removeElement($morceau);
            $morceau->removeFeaturing($this);
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
            $album->setArtiste($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            // set the owning side to null (unless already changed)
            if ($album->getArtiste() === $this) {
                $album->setArtiste(null);
            }
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
            $single->setArtiste($this);
        }

        return $this;
    }

    public function removeSingle(Single $single): self
    {
        if ($this->singles->contains($single)) {
            $this->singles->removeElement($single);
            // set the owning side to null (unless already changed)
            if ($single->getArtiste() === $this) {
                $single->setArtiste(null);
            }
        }

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
            $video->setArtiste($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getArtiste() === $this) {
                $video->setArtiste(null);
            }
        }

        return $this;
    }

   
    public function getSerieArtiste(): ?Serie
    {
        return $this->serie_artiste;
    }

    public function setSerieArtiste(?Serie $serie_artiste): self
    {
        $this->serie_artiste = $serie_artiste;

        return $this;
    }

    public function getSerieRealisateur(): ?Serie
    {
        return $this->serie_realisateur;
    }

    public function setSerieRealisateur(?Serie $serie_realisateur): self
    {
        $this->serie_realisateur = $serie_realisateur;

        return $this;
    }

    /**
     * @return Collection|Serie[]
     */
    public function getSeriesProducteur(): Collection
    {
        return $this->series_producteur;
    }

    public function addSeriesProducteur(Serie $seriesProducteur): self
    {
        if (!$this->series_producteur->contains($seriesProducteur)) {
            $this->series_producteur[] = $seriesProducteur;
            $seriesProducteur->addProducteur($this);
        }

        return $this;
    }

    public function removeSeriesProducteur(Serie $seriesProducteur): self
    {
        if ($this->series_producteur->contains($seriesProducteur)) {
            $this->series_producteur->removeElement($seriesProducteur);
            $seriesProducteur->removeProducteur($this);
        }

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

   

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPhotoCouverture(): ?Media
    {
        return $this->photoCouverture;
    }

    public function setPhotoCouverture(?Media $photoCouverture): self
    {
        $this->photoCouverture = $photoCouverture;

        return $this;
    }

    /**
     * @return Collection|MediaUnit[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(MediaUnit $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setUserPhotos($this);
        }

        return $this;
    }

    public function removePhoto(MediaUnit $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getUserPhotos() === $this) {
                $photo->setUserPhotos(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nom ? $this->nom : '';
    }

    /**
     * @return Collection|GenreMusical[]
     */
    public function getFavorisGenreMusicals(): Collection
    {
        return $this->favorisGenreMusicals;
    }

    public function addFavorisGenreMusical(GenreMusical $favorisGenreMusical): self
    {
        if (!$this->favorisGenreMusicals->contains($favorisGenreMusical)) {
            $this->favorisGenreMusicals[] = $favorisGenreMusical;
            $favorisGenreMusical->addUserFavori($this);
        }

        return $this;
    }

    public function removeFavorisGenreMusical(GenreMusical $favorisGenreMusical): self
    {
        if ($this->favorisGenreMusicals->contains($favorisGenreMusical)) {
            $this->favorisGenreMusicals->removeElement($favorisGenreMusical);
            $favorisGenreMusical->removeUserFavori($this);
        }

        return $this;
    }

    /**
     * @return Collection|GenreMusical[]
     */
    public function getJaimeGenreMusicals(): Collection
    {
        return $this->jaimeGenreMusicals;
    }

    public function addJaimeGenreMusical(GenreMusical $jaimeGenreMusical): self
    {
        if (!$this->jaimeGenreMusicals->contains($jaimeGenreMusical)) {
            $this->jaimeGenreMusicals[] = $jaimeGenreMusical;
            $jaimeGenreMusical->addUserJaime($this);
        }

        return $this;
    }

    public function removeJaimeGenreMusical(GenreMusical $jaimeGenreMusical): self
    {
        if ($this->jaimeGenreMusicals->contains($jaimeGenreMusical)) {
            $this->jaimeGenreMusicals->removeElement($jaimeGenreMusical);
            $jaimeGenreMusical->removeUserJaime($this);
        }

        return $this;
    }

    /**
     * @return Collection|GenreMusical[]
     */
    public function getJaimepasGenreMuscials(): Collection
    {
        return $this->jaimepasGenreMuscials;
    }

    public function addJaimepasGenreMuscial(GenreMusical $jaimepasGenreMuscial): self
    {
        if (!$this->jaimepasGenreMuscials->contains($jaimepasGenreMuscial)) {
            $this->jaimepasGenreMuscials[] = $jaimepasGenreMuscial;
            $jaimepasGenreMuscial->addUserJaimepa($this);
        }

        return $this;
    }

    public function removeJaimepasGenreMuscial(GenreMusical $jaimepasGenreMuscial): self
    {
        if ($this->jaimepasGenreMuscials->contains($jaimepasGenreMuscial)) {
            $this->jaimepasGenreMuscials->removeElement($jaimepasGenreMuscial);
            $jaimepasGenreMuscial->removeUserJaimepa($this);
        }

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
     * @return Collection|Article[]
     */
    public function getArticlesAime(): Collection
    {
        return $this->articles_aime;
    }

    public function addArticlesAime(Article $articlesAime): self
    {
        if (!$this->articles_aime->contains($articlesAime)) {
            $this->articles_aime[] = $articlesAime;
            $articlesAime->addUserJaime($this);
        }

        return $this;
    }

    public function removeArticlesAime(Article $articlesAime): self
    {
        if ($this->articles_aime->contains($articlesAime)) {
            $this->articles_aime->removeElement($articlesAime);
            $articlesAime->removeUserJaime($this);
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticlesAimepas(): Collection
    {
        return $this->articles_aimepas;
    }

    public function addArticlesAimepa(Article $articlesAimepa): self
    {
        if (!$this->articles_aimepas->contains($articlesAimepa)) {
            $this->articles_aimepas[] = $articlesAimepa;
            $articlesAimepa->addUserJaimepa($this);
        }

        return $this;
    }

    public function removeArticlesAimepa(Article $articlesAimepa): self
    {
        if ($this->articles_aimepas->contains($articlesAimepa)) {
            $this->articles_aimepas->removeElement($articlesAimepa);
            $articlesAimepa->removeUserJaimepa($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEventsUserJaime(): Collection
    {
        return $this->events_user_jaime;
    }

    public function addEventsUserJaime(Event $eventsUserJaime): self
    {
        if (!$this->events_user_jaime->contains($eventsUserJaime)) {
            $this->events_user_jaime[] = $eventsUserJaime;
            $eventsUserJaime->addUserJaime($this);
        }

        return $this;
    }

    public function removeEventsUserJaime(Event $eventsUserJaime): self
    {
        if ($this->events_user_jaime->contains($eventsUserJaime)) {
            $this->events_user_jaime->removeElement($eventsUserJaime);
            $eventsUserJaime->removeUserJaime($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEventsUserJaimepas(): Collection
    {
        return $this->events_user_jaimepas;
    }

    public function addEventsUserJaimepa(Event $eventsUserJaimepa): self
    {
        if (!$this->events_user_jaimepas->contains($eventsUserJaimepa)) {
            $this->events_user_jaimepas[] = $eventsUserJaimepa;
            $eventsUserJaimepa->addUserJaimepa($this);
        }

        return $this;
    }

    public function removeEventsUserJaimepa(Event $eventsUserJaimepa): self
    {
        if ($this->events_user_jaimepas->contains($eventsUserJaimepa)) {
            $this->events_user_jaimepas->removeElement($eventsUserJaimepa);
            $eventsUserJaimepa->removeUserJaimepa($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAuthor($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getAuthor() === $this) {
                $event->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Artiste[]
     */
    public function getAwardArtistes(): Collection
    {
        return $this->award_artistes;
    }

    public function addAwardArtiste(Artiste $awardArtiste): self
    {
        if (!$this->award_artistes->contains($awardArtiste)) {
            $this->award_artistes[] = $awardArtiste;
            $awardArtiste->addUser($this);
        }

        return $this;
    }

    public function removeAwardArtiste(Artiste $awardArtiste): self
    {
        if ($this->award_artistes->contains($awardArtiste)) {
            $this->award_artistes->removeElement($awardArtiste);
            $awardArtiste->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Artiste[]
     */
    public function getAwardArtistesNomines(): Collection
    {
        return $this->award_artistes_nomines;
    }

    public function addAwardArtistesNomine(Artiste $awardArtistesNomine): self
    {
        if (!$this->award_artistes_nomines->contains($awardArtistesNomine)) {
            $this->award_artistes_nomines[] = $awardArtistesNomine;
            $awardArtistesNomine->setArtiste($this);
        }

        return $this;
    }

    public function removeAwardArtistesNomine(Artiste $awardArtistesNomine): self
    {
        if ($this->award_artistes_nomines->contains($awardArtistesNomine)) {
            $this->award_artistes_nomines->removeElement($awardArtistesNomine);
            // set the owning side to null (unless already changed)
            if ($awardArtistesNomine->getArtiste() === $this) {
                $awardArtistesNomine->setArtiste(null);
            }
        }

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

public function getPlainPassword(): ?string
{
    return $this->plainPassword;
}

public function setPlainPassword(?string $plainPassword): self
{
    $this->plainPassword = $plainPassword;

    return $this;
}

/**
 * @return Collection|GestionTicket[]
 */
public function getTicketsAchetes(): Collection
{
    return $this->ticketsAchetes;
}

public function addTicketsAchete(GestionTicket $ticketsAchete): self
{
    if (!$this->ticketsAchetes->contains($ticketsAchete)) {
        $this->ticketsAchetes[] = $ticketsAchete;
        $ticketsAchete->setUser($this);
    }

    return $this;
}

public function removeTicketsAchete(GestionTicket $ticketsAchete): self
{
    if ($this->ticketsAchetes->contains($ticketsAchete)) {
        $this->ticketsAchetes->removeElement($ticketsAchete);
        // set the owning side to null (unless already changed)
        if ($ticketsAchete->getUser() === $this) {
            $ticketsAchete->setUser(null);
        }
    }

    return $this;
}

public function getResetToken(): ?string
{
    return $this->resetToken;
}

public function setResetToken(?string $resetToken): self
{
    $this->resetToken = $resetToken;

    return $this;
}
}
