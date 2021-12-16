<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\News;

use App\Entity\News\CategorieArticle as CategorieArticle;
use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Home\Tag;
use App\Entity\Home\User;
use App\Entity\Play\Morceau;
use App\Entity\Play\Single;
use App\Entity\Play\Video;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;


/**
 * @ORM\Entity(repositoryClass="App\Repository\News\ArticleRepository")
 * @ORM\Table(name="kossa_news_article")
 */
class Article
{
/**
 * Use constants to define configuration options that rarely change instead
 * of specifying them under parameters section in config/services.yaml file.
 *
 * See https://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
 */
public const NUM_ITEMS = 10;

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
private $title;

/**
 * @var string
 *
 * @ORM\Column(type="string")
 */
private $slug;

/**
 * @var string
 *
 * @ORM\Column(type="text")
 * @Assert\NotBlank(message="post.blank_summary")
 */
private $summary;

/**
 * @var string
 *
 * @ORM\Column(type="text", nullable=true)
 * @Assert\NotBlank(message="post.blank_content")
 * @Assert\Length(min=10, minMessage="post.too_short_content")
 */
private $content;

/**
 * @var \DateTime
 *
 * @ORM\Column(type="datetime")
 */
private $publishedAt;

/**
 * @var User
 *
 * @ORM\ManyToOne(targetEntity="App\Entity\Home\User")
 * @ORM\JoinColumn(nullable=false)
 */
private $author;

/**
 * 
 * @var Comment[]|ArrayCollection
 *
 * @ORM\OneToMany(
 *      targetEntity="App\Entity\News\Comment",
 *      mappedBy="article",
 *      orphanRemoval=true,
 *      cascade={"persist"}
 * )
 * @ORM\OrderBy({"publishedAt": "DESC"})
 */
private $comments;

/**
 * 
 * @var Tag[]|ArrayCollection
 *
 * @ORM\ManyToMany(targetEntity="App\Entity\Home\Tag", cascade={"persist"})
 * @ORM\JoinTable(name="kossa_new_article_tag")
 * @ORM\OrderBy({"name": "ASC"})
 * @Assert\Count(max="4", maxMessage="post.too_many_tags")
 */
private $tags;

/**
 * @ORM\ManyToOne(targetEntity="App\Entity\News\CategorieArticle", inversedBy="articles")
 */
private $categorie;

/**
 * @ORM\Column(type="datetime", nullable=true)
 */
private $dateRedaction;

/**
 * @ORM\Column(type="integer", nullable=true)
 */
private $vues = 0;

/**
 * @ORM\Column(type="boolean", nullable=false)
 */
private $publie;

/**
 * @Exclude
 * @ORM\ManyToOne(targetEntity="App\Entity\Play\Video", inversedBy="articles")
 */
private $video;

/**
 * @Exclude
 * @ORM\ManyToOne(targetEntity="App\Entity\Play\Single", inversedBy="articles")
 */
private $single;

/**
 * @Exclude
 * @ORM\ManyToOne(targetEntity="App\Entity\Play\Morceau", inversedBy="articles")
 */
private $morceau;

/**
 * @ORM\Column(type="boolean", nullable=false)
 */
private $hasVideo;


/**
 * @ORM\Column(type="boolean", nullable=false)
 */
private $hasPhoto;


/**
 * @ORM\Column(type="boolean", nullable=false)
 */
private $hasAudio;

/**
 * @Exclude
 * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
 */
private $photo;

private $photoUrl;
private $link;

/**
 * @ORM\Column(type="integer")
 */
private $jaime = 0;

/**
 * @ORM\Column(type="integer")
 */
private $jaimepas = 0;

/**
 * @Exclude
 * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="articles_aime")
 * @ORM\JoinTable(name="kossa_news_article_user_jaime")
 */
private $user_jaime;

/**
 * @Exclude
 * @ORM\ManyToMany(targetEntity="App\Entity\Home\User", inversedBy="articles_aimepas")
 * @ORM\JoinTable(name="kossa_news_article_user_jaimepas")
 */
private $user_jaimepas;



/**
 * @ORM\Column(type="integer")
 */
private $nbreTickets = 0;

/**
 * @Exclude
 * @ORM\Column(type="string", nullable=true)
 */
private $contentFormatter;

/**
 * @Exclude
 * @ORM\Column(type="text", nullable=true)
 */
private $rawContent;




public function __construct()
{
$this->dateRedaction = new \DateTime();
$this->publishedAt = new \DateTime();
$this->comments = new ArrayCollection();
$this->tags = new ArrayCollection();
$this->user_jaime = new ArrayCollection();
$this->user_jaimepas = new ArrayCollection();
}

public function getId(): ?int
{
return $this->id;
}

public function getTitle(): ?string
{
if($this->hasVideo){
return "[Video] ".$this->title;
}else if($this->hasPhoto){
return "[Photo] ".$this->title;
}else if($this->hasAudio){
return "[Audio] ".$this->title;
}

return $this->title;
}

public function setTitle(string $title): void
{
$this->title = $title;
}

public function getSlug(): ?string
{
return $this->slug;
}

public function setSlug(string $slug): void
{
$this->slug = $slug;
}

public function getContent(): ?string
{
return $this->content;
}

public function setContent(string $content): void
{
$this->content = $content;
}

public function getPublishedAt(): \DateTime
{
return $this->publishedAt;
}

public function setPublishedAt(\DateTime $publishedAt): void
{
$this->publishedAt = $publishedAt;
}

public function getAuthor(): ?User
{
return $this->author;
}

public function setAuthor(User $author): void
{
$this->author = $author;
}

public function getComments(): Collection
{
return $this->comments;
}

public function addComment(Comment $comment): void
{
$comment->setArticle($this);
if (!$this->comments->contains($comment)) {
$this->comments->add($comment);
}
}

public function removeComment(Comment $comment): void
{
$this->comments->removeElement($comment);
}

public function getSummary(): ?string
{
return $this->summary;
}

public function setSummary(string $summary): void
{
$this->summary = $summary;
}

public function addTag(Tag ...$tags): void
{
foreach ($tags as $tag) {
if (!$this->tags->contains($tag)) {
$this->tags->add($tag);
}
}
}

public function removeTag(Tag $tag): void
{
$this->tags->removeElement($tag);
}

public function getTags(): Collection
{
return $this->tags;
}

public function getCategorie(): ?CategorieArticle
{
return $this->categorie;
}

public function setCategorie(?CategorieArticle $categorie): self
{
$this->categorie = $categorie;

return $this;
}

public function getDateRedaction(): ?\DateTimeInterface
{
return $this->dateRedaction;
}

public function setDateRedaction(?\DateTimeInterface $dateRedaction): self
{
$this->dateRedaction = $dateRedaction;

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

public function getPublie(): ?bool
{
return $this->publie;
}

public function setPublie(?bool $publie): self
{
$this->publie = $publie;

return $this;
}

public function getVideo(): ?Video
{
return $this->video;
}

public function setVideo(?Video $video): self
{
$this->video = $video;

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



public function __toString() {
return $this->title ? $this->title : '';
}

public function getHasVideo(): ?bool
{
return $this->hasVideo;
}

public function setHasVideo(?bool $hasVideo): self
{
$this->hasVideo = $hasVideo;

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


public function getType(): ?string
{
return $this->type;
}

public function setType(?string $type): self
{
$this->type = $type;

return $this;
}

public function getNbreTickets(): ?int
{
return $this->nbreTickets;
}

public function setNbreTickets(int $nbreTickets): self
{
$this->nbreTickets = $nbreTickets;

return $this;
}

public function getContentFormatter(): ?string
{
return $this->contentFormatter;
}

public function setContentFormatter(?string $contentFormatter): self
{
$this->contentFormatter = $contentFormatter;

return $this;
}

public function getRawContent(): ?string
{
return $this->rawContent;
}

public function setRawContent(?string $rawContent): self
{
$this->rawContent = $rawContent;

return $this;
}

public function getHasPhoto(): ?bool
{
return $this->hasPhoto;
}

public function setHasPhoto(bool $hasPhoto): self
{
$this->hasPhoto = $hasPhoto;

return $this;
}

public function getHasAudio(): ?bool
{
return $this->hasAudio;
}

public function setHasAudio(bool $hasAudio): self
{
$this->hasAudio = $hasAudio;

return $this;
}

}
