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

use App\Entity\Home\User;
use App\Entity\Play\GenreMusical;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="App\Repository\News\CommentRepository")
 * @ORM\Table(name="kossa_news_comment")
 *
 */
class Comment
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
 * @Exclude
 * @var Article
 * @ORM\ManyToOne(targetEntity="App\Entity\News\Article", inversedBy="comments")
 * @ORM\JoinColumn(nullable=true)
 */
private $article;

/**
 * @var string
 *
 * @ORM\Column(type="text")
 * @Assert\NotBlank(message="comment.blank")
 * @Assert\Length(
 *     min=5,
 *     minMessage="comment.too_short",
 *     max=10000,
 *     maxMessage="comment.too_long"
 * )
 */
private $content;
private $authorPhotoUrl;

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
 * @Exclude
 * @ORM\ManyToOne(targetEntity="App\Entity\Play\GenreMusical", inversedBy="comments")
 */
private $genreMusical;

public function __construct()
{
$this->publishedAt = new \DateTime();
}

/**
 * @Assert\IsTrue(message="comment.is_spam")
 */
public function isLegitComment(): bool
{
$containsInvalidCharacters = false !== mb_strpos($this->content, '@');

return !$containsInvalidCharacters;
}

public function getId(): ?int
{
return $this->id;
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

public function getArticle(): ?Article
{
return $this->article;
}

public function setArticle(Article $article): void
{
$this->article = $article;
}

public function getGenreMusical(): ?GenreMusical
{
return $this->genreMusical;
}

public function setGenreMusical(?GenreMusical $genreMusical): self
{
$this->genreMusical = $genreMusical;

return $this;
}

public function getAuthorPhotoUrl()
{
return $this->authorPhotoUrl;
}

public function setAuthorPhotoUrl($authorPhotoUrl): self
{
$this->authorPhotoUrl = $authorPhotoUrl;

return $this;
}



}
