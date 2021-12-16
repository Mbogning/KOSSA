<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Repository\Play\GenreMusicalRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/api/news", name="api_news")
 * @author William MEKOMOU <williammekomou@gmail.com>
 */
class RestNewsController extends AbstractFOSRestController {

    /**
     * La liste de tous les genres.
     * @Rest\Get("/genres")
     *
     * @return Response
     */
    public function genres(GenreMusicalRepository $genreRepo): Response {
        $genres = $genreRepo->findAll();
        return $this->handleView($this->view($genres));
    }

}
