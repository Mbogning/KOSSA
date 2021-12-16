<?php

namespace App\Controller;

use App\Entity\News\Article;
use App\Entity\News\Comment;
use App\Entity\Play\GenreMusical;
use App\Repository\Event\CategorieEventRepository;
use App\Repository\Home\TagRepository;
use App\Repository\News\ArticleRepository;
use App\Repository\News\CategorieArticleRepository;
use App\Repository\News\CommentRepository;
use App\Repository\Play\GenreMusicalRepository;
use App\Repository\Play\TypeMusicalRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sonata\MediaBundle\Twig\Extension\MediaExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/news")
 * @author William MEKOMOU <williammekomou@gmail.com>
 */
class NewsController extends AbstractFOSRestController {

    /**
     * recupere les categories des articles
     * @Rest\Get("/json_categories")
     *
     * @return Response
     */
    public function categoriesJson(CategorieArticleRepository $categoryRepo): Response {
        $catarticles = $categoryRepo->findAll();
        return $this->handleView($this->view($catarticles));
    }

    /* public function indexkkk(Request $request, CategorieArticleRepository $categoryRepo, ArticleRepository $articleRepo, TagRepository $tagRepo): Response {

      if ($request->query->has('tag')) {
      $tag = $tagRepo->findOneBy(['name' => $request->query->get('tag')]);
      $latestArticles = $articleRepo->findNewsByTag($tag);
      } else if ($request->query->has('query')) {
      $query = $request->query->get('query');
      $latestArticles = $articleRepo->findNewsBySearchQuery($query);
      } else if ($request->query->has('category')) {
      $cat = $categoryRepo->findOneBy(['nom' => $request->query->get('category')]);
      $latestArticles = $articleRepo->findNewsByCategorie($cat);
      } else {
      $latestArticles = $articleRepo->findNewsByCategorie();
      }

      $catarticles = $categoryRepo->findAll();

      return $this->render('kossa/news/index.html.twig', ['articles_lenght' => count($latestArticles), 'categories' => $catarticles]);
      } */

    /**
     * recupere les types musicaux
     * @Rest\Get("/json_genres")
     *
     * @return Response
     */
    public function genresJson(MediaExtension $mediaextension, TypeMusicalRepository $typeRepo): Response {
        $types = $typeRepo->findAll();
        foreach ($types as $type) {
            foreach ($type->getGenreMusicals() as $genre) {
                $avatarpath = $mediaextension->path($genre->getPhoto(), 'normal');
                $genre->setPhotoUrl($avatarpath);
            }
        }

        return $this->handleView($this->view($types));
    }

    /**
     * @Route("/genrek/{slug}", methods={"GET"}, name="kossa_news_genre")
     *
     */
    public function genreShow(Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo): Response {
        //on voit le nombre de vues
        if ($this->getUser()) {
            $login = 1;
        } else {
            $login = 0;
        }
        if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_news_genre_views_' . $genre->getId())) {
                $session->set('kossa_news_genre_views_' . $genre->getId(), true);
                $genreRepo->incrementViews($genre->getId());
            }
        }

        return $this->render('kossa/news/genre.html.twig', ['login' => $login, 'genre' => $genre]);
    }

    /**
     * recupere un genre selon son slug
     * @Rest\Get("/json_genre/{slug}")
     *
     * @return Response
     */
    public function genreJson(MediaExtension $mediaextension, Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo): Response {
        //on voit le nombre de vues
        if ($this->getUser()) {
            $login = 1;
        } else {
            $login = 0;
        }
        if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_news_genre_views_' . $genre->getId())) {
                $session->set('kossa_news_genre_views_' . $genre->getId(), true);
                $genreRepo->incrementViews($genre->getId());
            }
        }
        $genre->setLink($login);
        $avatarpath = $mediaextension->path($genre->getPhoto(), 'normal');
        $genre->setPhotoUrl($avatarpath);
        return $this->handleView($this->view($genre));
    }

    /**
     * permet d'ajouter ou de retirer les j'aime sur un genre
     * @Rest\Get("/json_managegenrejaime/{id}")
     */
    public function manageGenreJaimeJson(Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo) {
        if ($this->getUser()) {
            if ($genre->getUserJaime()->contains($this->getUser())) {
                $genre->removeUserJaime($this->getUser());
                $genreRepo->decrementJaime($genre->getId());
            } else {
                $genre->addUserJaime($this->getUser());
                $genreRepo->incrementJaime($genre->getId());

                if ($genre->getUserJaimepas()->contains($this->getUser())) {
                    $genre->removeUserJaimepa($this->getUser());
                    $genreRepo->decrementJaimePas($genre->getId());
                }
            }
        } else if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_news_genre_jaime_' . $genre->getId())) {
                $session->set('kossa_news_genre_jaime_' . $genre->getId(), true);
                $genreRepo->incrementJaime($genre->getId());

                //on voit s'il n'a pas aimé pour enlever
                if ($session->get('kossa_news_genre_jaimepas_' . $genre->getId())) {
                    $session->remove('kossa_news_genre_jaimepas_' . $genre->getId());
                    $genreRepo->decrementJaimePas($genre->getId());
                }
            } else {
                $session->remove('kossa_news_genre_jaime_' . $genre->getId());
                $genreRepo->decrementJaime($genre->getId());
            }
        }
        //on raffraichit tout
        $this->getDoctrine()->getManager()->flush();
        //$genref = $genreRepo->find($genre->getId());
        return $this->handleView($this->view($genre));
    }

    /**
     * permet d'ajouter ou de retirer les j'aime pas sur un genre
     * @Rest\Get("/json_managegenrejaimepas/{id}")
     */
    public function manageGenreJaimePasJson(Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo) {
        if ($this->getUser()) {
            if ($genre->getUserJaimepas()->contains($this->getUser())) {
                $genre->removeUserJaimepa($this->getUser());
                $genreRepo->decrementJaimePas($genre->getId());
                $this->getDoctrine()->getManager()->flush();
            } else {
                $genre->addUserJaimepa($this->getUser());
                $genreRepo->incrementJaimePas($genre->getId());
                $this->getDoctrine()->getManager()->flush();

                if ($genre->getUserJaime()->contains($this->getUser())) {
                    $genre->removeUserJaime($this->getUser());
                    $genreRepo->decrementJaime($genre->getId());
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_news_genre_jaimepas_' . $genre->getId())) {
                $session->set('kossa_news_genre_jaimepas_' . $genre->getId(), true);
                $genreRepo->incrementJaimePas($genre->getId());

                //on voit s'il n'a pas aimé pour enlever
                if ($session->get('kossa_news_genre_jaime_' . $genre->getId())) {
                    $session->remove('kossa_news_genre_jaime_' . $genre->getId());
                    $genreRepo->decrementJaime($genre->getId());
                }
            } else {
                $session->remove('kossa_news_genre_jaimepas_' . $genre->getId());
                $genreRepo->decrementJaimePas($genre->getId());
            }
        }

        //on raffraichit tout
        $this->getDoctrine()->getManager()->flush();
        //$genref = $genreRepo->find($genre->getId());
        return $this->handleView($this->view($genre));
    }

    /**
     * permet d'ajouter genre genre comme favoiris ou pas
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Rest\Get("/json_managegenrefavoris/{id}")
     */
    public function manageGenreFavorisJson(Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo) {
        if ($genre->getUserFavoris()->contains($this->getUser())) {
            $genre->removeUserFavori($this->getUser());
        } else {
            $genre->addUserFavori($this->getUser());
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->handleView($this->view($genre));
    }

    /**
     * permet de savoir si un user a aimé un genre
     * @Rest\Get("/json_genreaime/{slug}")
     */
    public function genreaimejson(Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo) {
        $result = false;
        if ($this->getUser()) {
            if ($genre->getUserJaime()->contains($this->getUser())) {
                $result = true;
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->get('kossa_news_genre_jaime_' . $genre->getId())) {
                $result = true;
            }
        }
        return $this->handleView($this->view($result));
    }

    /**
     * permet de savoir si un suer n'a pas aimé un genre
     * @Rest\Get("/json_genreaimepas/{slug}")
     */
    public function genreaimepasjson(Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo) {
        $result = false;
        if ($this->getUser()) {
            if ($genre->getUserJaimepas()->contains($this->getUser())) {
                $result = true;
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->get('kossa_news_genre_jaimepas_' . $genre->getId())) {
                $result = true;
            }
        }
        return $this->handleView($this->view($result));
    }

    /**
     * permet de savoir si un user a mis un genre comme favoiris
     * @Rest\Get("/json_genrefavoris/{slug}")
     */
    public function genrefavorisjson(Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo) {
        $result = false;

        if ($genre->getUserFavoris()->contains($this->getUser())) {
            $result = true;
        }

        return $this->handleView($this->view($result));
    }

    /**
     * recupere les commentaires d'un genre
     * @Rest\Get("/json_genrecomment/{id}/{offset}/{limit}")
     */
    public function genrecommentjson(MediaExtension $mediaextension, Request $request, CommentRepository $commentRepo) {
        $offset = $request->get('offset');
        $limit = $request->get('limit');

        $comments = $commentRepo->findBy(['genreMusical' => $request->get('id')], ['publishedAt' => 'DESC'], $limit, $offset);
        foreach ($comments as $comment) {
            $avatarpath = $mediaextension->path($comment->getAuthor()->getPhoto(), 'normal');
            $comment->setAuthorPhotoUrl($avatarpath);
        }
        return $this->handleView($this->view($comments));
    }

    /**
     * permet de publir les commentaires sur un genre
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/json_genrecommentpublier/{id}")
     */
    public function genrecommentpublierjson(MediaExtension $mediaextension, Request $request, GenreMusical $genre, GenreMusicalRepository $genreRepo) {
        $data = json_decode($request->getContent(), true);
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setGenreMusical($genre);
        $comment->setContent($data['comment']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $avatarpath = $mediaextension->path($comment->getAuthor()->getPhoto(), 'normal');
        $comment->setAuthorPhotoUrl($avatarpath);

        return $this->handleView($this->view($comment));
    }

    /**
     * recupere le liste des articles
     * @Rest\Get("/json_articles/{offset}/{limit}/{type}/{query}")
     */
    public function articlesjson(MediaExtension $mediaextension, Request $request, CategorieArticleRepository $catarticleRepo, ArticleRepository $articleRepo, TagRepository $tagRepo, CategorieArticleRepository $categoryRepo) {
        $offset = $request->get('offset');
        $limit = $request->get('limit');
        $type = $request->get('type');
        $query = $request->get('query');
        
              $articles = $articleRepo->findNewsBySearchQuery($query,null, $limit, $offset);
      
        
        if ($type == "category") {
            $cat = $categoryRepo->findOneBy(['nom' => $query]);
            $articles = $articleRepo->findNewsByCategorie($cat, $limit, $offset);
        } else if ($type == "tag") {
            $tag = $tagRepo->findOneBy(['name' => $query]);
            $articles = $articleRepo->findNewsByTag($tag, $limit, $offset);
        } else if ($type == "query") {
            $articles = $articleRepo->findNewsBySearchQuery($query,null, $limit, $offset);
        } else {
            $articles = $articleRepo->findNewsByCategorie(null, $limit, $offset);
        }

        foreach ($articles as $article) {
            $photopath = $mediaextension->path($article->getPhoto(), 'normal');
            $article->setPhotoUrl($photopath);
            //$url = $this->generateUrl('kossa_news_actualite', array('slug' => $article->getSlug()));
            //$article->setLink($url);
        }
        return $this->handleView($this->view($articles));
    }

    /* public function articleShow(Request $request, Article $article, ArticleRepository $articleRepo): Response {

      //on voit le nombre de vues
      if ($this->getUser()) {
      $login = 1;
      } else {
      $login = 0;
      }
      if ($request->hasSession() && ($session = $request->getSession())) {
      if (!$session->get('kossa_news_genre_views_' . $article->getId())) {
      $session->set('kossa_news_genre_views_' . $article->getId(), true);
      $articleRepo->incrementViews($article->getId());
      }
      }
      return $this->render('kossa/news/article.html.twig', ['login' => $login, 'article' => $article]);
      } */

    /**
     * permet d'ajouter ou retirer les j'aime sur un article
     * @Rest\Get("/json_managearticlejaime/{id}")
     */
    public function manageArticleJaimeJson(Request $request, Article $article, ArticleRepository $articleRepo) {
        if ($this->getUser()) {
            if ($article->getUserJaime()->contains($this->getUser())) {
                $article->removeUserJaime($this->getUser());
                $articleRepo->decrementJaime($article->getId());
            } else {
                $article->addUserJaime($this->getUser());
                $articleRepo->incrementJaime($article->getId());

                if ($article->getUserJaimepas()->contains($this->getUser())) {
                    $article->removeUserJaimepa($this->getUser());
                    $articleRepo->decrementJaimePas($article->getId());
                }
            }
        } else if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_news_article_jaime_' . $article->getId())) {
                $session->set('kossa_news_article_jaime_' . $article->getId(), true);
                $articleRepo->incrementJaime($article->getId());

                //on voit s'il n'a pas aimé pour enlever
                if ($session->get('kossa_news_article_jaimepas_' . $article->getId())) {
                    $session->remove('kossa_news_article_jaimepas_' . $article->getId());
                    $articleRepo->decrementJaimePas($article->getId());
                }
            } else {
                $session->remove('kossa_news_article_jaime_' . $article->getId());
                $articleRepo->decrementJaime($article->getId());
            }
        }
        //on raffraichit tout
        $this->getDoctrine()->getManager()->flush();
        //$genref = $genreRepo->find($genre->getId());
        return $this->handleView($this->view($article));
    }

    /**
     * permet d'ajouter ou enler les j'aime pas sur un article
     * @Rest\Get("/json_managearticlejaimepas/{id}")
     */
    public function manageArticleJaimePasJson(Request $request, Article $article, ArticleRepository $articleRepo) {
        if ($this->getUser()) {
            if ($article->getUserJaimepas()->contains($this->getUser())) {
                $article->removeUserJaimepa($this->getUser());
                $articleRepo->decrementJaimePas($article->getId());
                $this->getDoctrine()->getManager()->flush();
            } else {
                $article->addUserJaimepa($this->getUser());
                $articleRepo->incrementJaimePas($article->getId());
                $this->getDoctrine()->getManager()->flush();

                if ($article->getUserJaime()->contains($this->getUser())) {
                    $article->removeUserJaime($this->getUser());
                    $articleRepo->decrementJaime($article->getId());
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_news_article_jaimepas_' . $article->getId())) {
                $session->set('kossa_news_article_jaimepas_' . $article->getId(), true);
                $articleRepo->incrementJaimePas($article->getId());

                //on voit s'il n'a pas aimé pour enlever
                if ($session->get('kossa_news_article_jaime_' . $article->getId())) {
                    $session->remove('kossa_news_article_jaime_' . $article->getId());
                    $articleRepo->decrementJaime($article->getId());
                }
            } else {
                $session->remove('kossa_news_article_jaimepas_' . $article->getId());
                $articleRepo->decrementJaimePas($article->getId());
            }
        }

        //on raffraichit tout
        $this->getDoctrine()->getManager()->flush();
        //$genref = $articleRepo->find($article->getId());
        return $this->handleView($this->view($article));
    }

    /**
     * permet de savoir si un user a aimé un article
     * @Rest\Get("/json_articleaime/{slug}")
     */
    public function articleaimejson(Request $request, Article $article, ArticleRepository $articleRepo) {
        $result = false;
        if ($this->getUser()) {
            if ($article->getUserJaime()->contains($this->getUser())) {
                $result = true;
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->get('kossa_news_article_jaime_' . $article->getId())) {
                $result = true;
            }
        }
        return $this->handleView($this->view($result));
    }

    /**
     * permet de savoir si un user n'a pas aimé un article
     * @Rest\Get("/json_articleaimepas/{slug}")
     */
    public function articleaimepasjson(Request $request, Article $article, ArticleRepository $articleRepo) {
        $result = false;
        if ($this->getUser()) {
            if ($article->getUserJaimepas()->contains($this->getUser())) {
                $result = true;
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->get('kossa_news_article_jaimepas_' . $article->getId())) {
                $result = true;
            }
        }
        return $this->handleView($this->view($result));
    }

    /**
     * permet de recuperer les aricles en relation avec d'autres articles
     * @Rest\Get("/json_articlefeaturing/{slug}")
     */
    public function articlefeaturingjson(MediaExtension $mediaextension, Request $request, Article $article, ArticleRepository $articleRepo) {
        $query ="";// $article->getTitle();
        foreach ($article->getTags() as $tag) {
            $query = $query . " " . $tag->getName();
        }
        
        //dump($query);
        //die;
        $articles = [];//$articleRepo->findNewsBySearchQuery($query, $article, 4);

        foreach ($articles as $article) {
            $avatarpath = $mediaextension->path($article->getPhoto(), 'normal');
            $article->setPhotoUrl($avatarpath);
        }

        return $this->handleView($this->view($articles));
    }

    /**
     * recupere les commentaires d'un article
     * @Rest\Get("/json_articlecomment/{id}/{offset}/{limit}")
     */
    public function articlecommentjson(MediaExtension $mediaextension, Request $request, CommentRepository $commentRepo) {
        $offset = $request->get('offset');
        $limit = $request->get('limit');

        $comments = $commentRepo->findBy(['article' => $request->get('id')], ['publishedAt' => 'DESC'], $limit, $offset);
        foreach ($comments as $comment) {
            $avatarpath = $mediaextension->path($comment->getAuthor()->getPhoto(), 'normal');
            $comment->setAuthorPhotoUrl($avatarpath);
        }
        return $this->handleView($this->view($comments));
    }

    /**
     * permet de publier les commentaires sur un article
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/json_articlecommentpublier/{id}")
     */
    public function articlecommentpublierjson(MediaExtension $mediaextension, Request $request, Article $article, ArticleRepository $articleRepo) {
        $data = json_decode($request->getContent(), true);
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setArticle($article);
        $comment->setContent($data['comment']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $avatarpath = $mediaextension->path($comment->getAuthor()->getPhoto(), 'normal');
        $comment->setAuthorPhotoUrl($avatarpath);

        return $this->handleView($this->view($comment));
    }

    /**
     * recupere un article selon son slug
     * @Rest\Get("/json_article/{slug}")
     *
     * @return Response
     */
    public function articleJson(MediaExtension $mediaextension, Request $request, Article $article, ArticleRepository $articleRepo): Response {
        //on voit le nombre de vues
        if ($this->getUser()) {
            $login = 1;
        } else {
            $login = 0;
        }
        $article->setLink($login);
        if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_news_genre_views_' . $article->getId())) {
                $session->set('kossa_news_genre_views_' . $article->getId(), true);
                $articleRepo->incrementViews($article->getId());
            }
        }
        $photopath = $mediaextension->path($article->getPhoto(), 'normal');
        $article->setPhotoUrl($photopath);

        $photoauthor = $mediaextension->path($article->getAuthor()->getPhoto(), 'normal');
        $article->getAuthor()->setPhotoUrl($photoauthor);

        return $this->handleView($this->view($article));
    }

    /**
     * @Route("/{route1}/{route2}", defaults={"route1": null,"route2": null}, name="kossa_news_index")
     */
    public function index(): Response {
        return $this->render('kossa/news/index.html.twig');
    }

}
