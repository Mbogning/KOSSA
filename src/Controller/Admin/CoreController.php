<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Controller\CoreController as BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CoreController extends Controller {

    /**
     * [dashboardAction description]
     * @return [type] [description]
     * @Route("admin-kossa/dashboard")
     */
    public function dashboardAction() {
        $em = $this->getDoctrine()->getManager();
        /* $awards = $em->getRepository('KossaEventBundle:Award')->findAll();
          $showcase = $em->getRepository('KossaEventBundle:Showcase')->findAll();
          $concert = $em->getRepository('KossaEventBundle:Concert')->findAll();
          $festival = $em->getRepository('KossaEventBundle:Festival')->findAll();
          $concert_live = $em->getRepository('KossaEventBundle:Concert_live')->findAll();



          $actualite = $em->getRepository('KossaNewsBundle:Actualite')->findAll();
          $publication_des_artistes = $em->getRepository('KossaNewsBundle:PublicationDesArtistes')->findAll();


         */
        $parameters = [
            'base_template' => $this->getBaseTemplate(),
            'admin_pool' => $this->container->get('sonata.admin.pool'),
            'awards' => [],
            // 'showcase' => $showcase,
            'showcase' => [],
            'concert' => [],
            'festival' => [],
            'concert_live' => [],
            'actualite' => [],
            'publication_des_artistes' => [],
        ];

        if (!$this->getCurrentRequest()->isXmlHttpRequest()) {
            $parameters['breadcrumbs_builder'] = $this->get('sonata.admin.breadcrumbs_builder');
        }

        return $this->render($this->getAdminPool()->getTemplate('dashboard'), $parameters);
    }

    /**
     * @return [type] [description]
     *
     * @Route("admin/ajax/stats", options={"expose"=true}, name="ajax_stats")
     */
    public function ajaxStatsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();


        throw new NotFoundHttpException();
    }

    /**
     * [getNbOfMonths description]
     * @param  [type] $repo [description]
     * @return [type]       [description]
     */
    private function getNbOfMonths($repo) {
        $user = $this->getUser();

        if (!$user) {
            throw new NotfoundHttpException();
        }

        $months = array(
            'janvier' => 1,
            'fevrier' => 2,
            'mars' => 3,
            'avril' => 4,
            'avril' => 4,
            'mai' => 5,
            'juin' => 6,
            'juillet' => 7,
            'aout' => 8,
            'septembre' => 9,
            'octobre' => 10,
            'novembre' => 11,
            'decembre' => 12
        );

        $nbCmdes = array();

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            foreach ($months as $key => $value) {
                $date = new \DateTime('01-' . $value . '-' . date('Y'));

                $nbCmdes[] = $repo->listByDate($date, $user)[0][1];
            }
        } else {
            foreach ($months as $key => $value) {
                $date = new \DateTime('01-' . $value . '-' . date('Y'));

                $nbCmdes[] = $repo->listByDate($date)[0][1];
            }
        }

        return $nbCmdes;
    }

    /**
     * @return Pool
     */
    protected function getAdminPool() {
        return $this->container->get('sonata.admin.pool');
    }

    /**
     * @return SearchHandler
     */
    protected function getSearchHandler() {
        return $this->get('sonata.admin.search.handler');
    }

    /**
     * @return string
     */
    protected function getBaseTemplate() {
        if ($this->getCurrentRequest()->isXmlHttpRequest()) {
            return $this->getAdminPool()->getTemplate('ajax');
        }

        return $this->getAdminPool()->getTemplate('layout');
    }

    /**
     * Get the request object from the container.
     *
     * @return Request
     */
    private function getCurrentRequest() {
        // NEXT_MAJOR: simplify this when dropping sf < 2.4
        if ($this->container->has('request_stack')) {
            return $this->container->get('request_stack')->getCurrentRequest();
        }

        return $this->container->get('request');
    }

    /**
     * [sumArray description]
     * @param  [type] $array1 [description]
     * @param  [type] $array2 [description]
     * @return [type]         [description]
     */
    private function sumArray($array1, $array2) {
        $sum = array_map(function (...$arrays) {
            return array_sum($arrays);
        }, $array1, $array2);

        return $sum;
    }

}
