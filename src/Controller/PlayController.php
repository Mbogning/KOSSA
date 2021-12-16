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

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Security\PostVoter;
use App\Utils\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/play")
 * @author Junior MBOGNING <juniormbogning@gmail.com>
 */
class PlayController extends AbstractController
{
    /**
     * @Route("/{route1}/{route2}/{route3}", defaults={"route1": null,"route2": null,"route3": null}, name="kossa_play_index")
     */
    public function index(): Response
    {
        
        return $this->render('kossa/play/index.html.twig', []);
    }

    
}
