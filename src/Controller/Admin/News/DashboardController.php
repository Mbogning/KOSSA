<?php

namespace App\Controller\Admin\News;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller {

    /**
     * 
     * @return [type] [description]
     *
     * @Route("/admin-kossa/news", name="admin_news_dashboard")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        /*$awards = $em->getRepository('KossaEventBundle:Award')->findAll();
        $showcase = $em->getRepository('KossaEventBundle:Showcase')->findAll();
        $concert = $em->getRepository('KossaEventBundle:Concert')->findAll();
        $festival = $em->getRepository('KossaEventBundle:Festival')->findAll();
        $concert_live = $em->getRepository('KossaEventBundle:Concert_live')->findAll();
*/

        return $this->render('kossa/event/admin/admin_dashboard.html.twig', array(
            //'awards' => $awards,
            'awards' => [],
            'showcase' => [],
            'concert' => [],
            'festival' => [],
            'concert_live' => [],
        ));
    }
    
   

}
