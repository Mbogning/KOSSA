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

use App\Entity\Event\CommentEvent;
use App\Entity\Event\Event;
use App\Entity\Event\GestionTicket;
use App\Entity\Event\Ticket;
use App\Entity\Home\GuestUser;
use App\Events;
use App\Repository\Event\ArtisteRepository;
use App\Repository\Event\CategorieAwardRepository;
use App\Repository\Event\CategorieEventRepository;
use App\Repository\Event\CommentEventRepository;
use App\Repository\Event\EventRepository;
use App\Repository\Event\GestionTicketRepository;
use App\Repository\Event\TicketRepository;
use App\Repository\Home\GuestUserRepository;
use App\Repository\Home\TagRepository;
use App\Repository\News\CategorieArticleRepository;
use App\Repository\PostRepository;
use App\Security\PostVoter;
use App\Utils\Slugger;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sonata\MediaBundle\Twig\Extension\MediaExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event")
 * @author William MEKOMOU <williammekomou@gmail.com>
 */
class EventController extends AbstractFOSRestController {

    /**
     * @Route("/", methods={"GET"}, name="kossa_event_events")
     * @Route("/", methods={"GET"}, name="kossa_event_index")
     */
    public function index(Request $request, CategorieEventRepository $cateeventRepo, EventRepository $eventRepo, TagRepository $tagRepo): Response {
        if ($request->query->has('tag')) {
            $tag = $tagRepo->findOneBy(['name' => $request->query->get('tag')]);
            $latestEvents = $eventRepo->findEventByTag($tag);
        } else if ($request->query->has('query')) {
            $query = $request->query->get('query');
            $latestEvents = $eventRepo->findEventBySearchQuery($query);
        } else if ($request->query->has('category')) {
            $cat = $cateeventRepo->findOneBy(['nom' => $request->query->get('category')]);
            $latestEvents = $eventRepo->findEventByCategorie($cat);
        } else {
            $latestEvents = $eventRepo->findEventByCategorie();
        }

        $catevents = $cateeventRepo->findAll();

        return $this->render('kossa/event/index.html.twig', ['event_lenght' => count($latestEvents), 'categories' => $catevents, 'accueil' => 1]);
    }
    
   /**
     * @Route("/tickets", methods={"GET"}, name="kossa_event_tickets")
     */
    public function tickets(Request $request, CategorieEventRepository $cateeventRepo, GestionTicketRepository $gestionticketRepo): Response {
        if ($request->query->has('queryticket')) {
            $query = $request->query->get('queryticket');
            $tickets = $gestionticketRepo->findTicketBySearchQuery($this->getUser(),$query);
        } else if ($request->query->has('categoryticket')) {
            $cat = $cateeventRepo->findOneBy(['nom' => $request->query->get('categoryticket')]);
            $tickets = $gestionticketRepo->findTicketByCategorie($this->getUser(),$cat);
        } else {
            $tickets = $this->getUser()->getTicketsAchetes();
        }

        $catevents = $cateeventRepo->findAll();

        return $this->render('kossa/event/tickets.html.twig', ['tickets' => $tickets, 'categories' => $catevents]);
    }

    /**
     * @Route("/concert/", methods={"GET"}, name="kossa_event_concert")
     */
    public function concert(): Response {
        return $this->render('kossa/event/event_type.html.twig', ['query' => 'concert', 'accueil' => 0]);
    }

    /**
     * @Route("/festival/", methods={"GET"}, name="kossa_event_festival")
     */
    public function festival(): Response {
        return $this->render('kossa/event/event_type.html.twig', ['query' => 'festival', 'accueil' => 0]);
    }

    /**
     * @Route("/showcase/", methods={"GET"}, name="kossa_event_showcase")
     */
    public function showcase(): Response {
        return $this->render('kossa/event/event_type.html.twig', ['query' => 'showcase', 'accueil' => 0]);
    }

    /**
     * @Route("/award/", methods={"GET"}, name="kossa_event_award")
     */
    public function award(): Response {
        return $this->render('kossa/event/event_type.html.twig', ['query' => 'award', 'accueil' => 0]);
    }

    /**
     * recupere le liste des évènements
     * @Rest\Get("/json_evenements/{offset}/{limit}/{type}/{query}")
     */
    public function evenementsjson(MediaExtension $mediaextension, Request $request, CategorieEventRepository $cateventRepo, EventRepository $eventRepo, TagRepository $tagRepo) {
        $offset = $request->get('offset');
        $limit = $request->get('limit');
        $type = $request->get('type');
        $query = $request->get('query');

        if ($type == "categorie") {
            $cat = $cateventRepo->findOneBy(['nom' => $query]);
            $events = $eventRepo->findEventByCategorie($cat, $limit, $offset);
        } else if ($type == "tag") {
            $tag = $tagRepo->findOneBy(['name' => $query]);
            $events = $eventRepo->findEventByTag($tag, $limit, $offset);
        } else if ($type == "query") {
            $events = $eventRepo->findEventBySearchQuery($query, $limit, $offset);
        } else {
            $events = $eventRepo->findEventByCategorie(null, $limit, $offset);
        }

        foreach ($events as $event) {
            $photopath = $mediaextension->path($event->getPhoto(), 'normal');
            $event->setPhotoUrl($photopath);
            $url = $this->generateUrl('kossa_event_event', array('slug' => $event->getSlug()));
            $event->setLink($url);
        }
        return $this->handleView($this->view($events));
    }

    /**
     * recupere les categories d'un award
     * @Rest\Get("/json_awardcategorie/{eventid}")
     */
    public function awardcategoriesjson(MediaExtension $mediaextension, Request $request, CategorieEventRepository $cateventRepo, EventRepository $eventRepo, TagRepository $tagRepo) {
        $query = $request->get('eventid');
        $event = $eventRepo->find($query);

        foreach ($event->getCategorieAwards() as $categorie) {
            //on met le token a jour pour les users ayant voté
            foreach ($categorie->getArtistes() as $vote) {
                $artiste = $vote->getArtiste();
                if ($this->getUser() && $vote->getUsers()->contains($this->getUser())) {
                    $vote->setHasvoted(true);
                }/* else if (!$this->getUser()) {
                  if ($request->hasSession() && ($session = $request->getSession())) {
                  if ($session->get('kossa_event_artiste_vote_' . $artiste->getId())) {
                  $vote->setHasvoted(true);
                  }
                  }
                  } */

                $photopath = $mediaextension->path($artiste->getPhoto(), 'normal');
                $vote->getArtiste()->setPhotoUrl($photopath);
            }
        }



        return $this->handleView($this->view($event->getCategorieAwards()));
    }

    /**
     * @Route("/event/{slug}", methods={"GET"}, name="kossa_event_event")
     *
     */
    public function eventShow(Request $request, Event $event, EventRepository $eventRepo): Response {

        //on voit le nombre de vues
        if ($this->getUser()) {
            $login = 1;
        } else {
            $login = 0;
        }
        if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_event_event_views_' . $event->getId())) {
                $session->set('kossa_event_event_views_' . $event->getId(), true);
                $eventRepo->incrementViews($event->getId());
            }
            
            //pour raffriachir la page apres paiement
        }
        return $this->render('kossa/event/event.html.twig', ['login' => $login, 'event' => $event]);
    }

    /**
     * recupere un event selon son id
     * @Rest\Get("/json_event/{id}")
     *
     * @return Response
     */
    public function eventJson(Event $event): Response {
        return $this->handleView($this->view($event));
    }

    /**
     * recupere les tickets actifs d'un event
     * @Rest\Get("/json_event_tickets/{id}")
     *
     * @return Response
     */
    public function eventTicketsJson(Event $event, TicketRepository $ticketRepo): Response {
        $tickets = $ticketRepo->findBy(['event' => $event, 'active' => true]);
        return $this->handleView($this->view($tickets));
    }

    /**
     * permet de savoir si un user a aimé un event
     * @Rest\Get("/json_eventaime/{id}")
     */
    public function eventaimejson(Request $request, Event $event) {
        $result = false;
        if ($this->getUser()) {
            if ($event->getUserJaime()->contains($this->getUser())) {
                $result = true;
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->get('kossa_event_event_jaime_' . $event->getId())) {
                $result = true;
            }
        }
        return $this->handleView($this->view($result));
    }

    /**
     * permet de savoir si un user n'a pas aimé un event
     * @Rest\Get("/json_eventaimepas/{id}")
     */
    public function eventaimepasjson(Request $request, Event $event) {
        $result = false;
        if ($this->getUser()) {
            if ($event->getUserJaimepas()->contains($this->getUser())) {
                $result = true;
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->get('kossa_event_event_jaimepas_' . $event->getId())) {
                $result = true;
            }
        }
        return $this->handleView($this->view($result));
    }

    /**
     * permet d'ajouter ou retirer les j'aime sur un event
     * @Rest\Get("/json_manageeventjaime/{id}")
     */
    public function manageEventJaimeJson(Request $request, Event $event, EventRepository $eventRepo) {
        if ($this->getUser()) {
            if ($event->getUserJaime()->contains($this->getUser())) {
                $event->removeUserJaime($this->getUser());
                $eventRepo->decrementJaime($event->getId());
            } else {
                $event->addUserJaime($this->getUser());
                $eventRepo->incrementJaime($event->getId());

                if ($event->getUserJaimepas()->contains($this->getUser())) {
                    $event->removeUserJaimepa($this->getUser());
                    $eventRepo->decrementJaimePas($event->getId());
                }
            }
        } else if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_event_event_jaime_' . $event->getId())) {
                $session->set('kossa_event_event_jaime_' . $event->getId(), true);
                $eventRepo->incrementJaime($genre->getId());

                //on voit s'il n'a pas aimé pour enlever
                if ($session->get('kossa_event_event_jaimepas_' . $event->getId())) {
                    $session->remove('kossa_event_event_jaimepas_' . $event->getId());
                    $eventRepo->decrementJaimePas($event->getId());
                }
            } else {
                $session->remove('kossa_event_event_jaime_' . $event->getId());
                $eventRepo->decrementJaime($event->getId());
            }
        }
        //on raffraichit tout
        $this->getDoctrine()->getManager()->flush();
        //$genref = $genreRepo->find($genre->getId());
        return $this->handleView($this->view($event));
    }

    /**
     * permet d'ajouter ou enler les j'aime pas sur un event
     * @Rest\Get("/json_manageeventjaimepas/{id}")
     */
    public function manageEventJaimePasJson(Request $request, Event $event, EventRepository $eventRepo) {
        if ($this->getUser()) {
            if ($event->getUserJaimepas()->contains($this->getUser())) {
                $event->removeUserJaimepa($this->getUser());
                $eventRepo->decrementJaimePas($event->getId());
                $this->getDoctrine()->getManager()->flush();
            } else {
                $event->addUserJaimepa($this->getUser());
                $eventRepo->incrementJaimePas($event->getId());
                $this->getDoctrine()->getManager()->flush();

                if ($event->getUserJaime()->contains($this->getUser())) {
                    $event->removeUserJaime($this->getUser());
                    $eventRepo->decrementJaime($event->getId());
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        } else
        if ($request->hasSession() && ($session = $request->getSession())) {
            if (!$session->get('kossa_event_event_jaimepas_' . $event->getId())) {
                $session->set('kossa_event_event_jaimepas_' . $event->getId(), true);
                $eventRepo->incrementJaimePas($event->getId());

                //on voit s'il n'a pas aimé pour enlever
                if ($session->get('kossa_event_event_jaime_' . $event->getId())) {
                    $session->remove('kossa_event_event_jaime_' . $event->getId());
                    $eventRepo->decrementJaime($event->getId());
                }
            } else {
                $session->remove('kossa_event_event_jaimepas_' . $event->getId());
                $eventRepo->decrementJaimePas($event->getId());
            }
        }

        //on raffraichit tout
        $this->getDoctrine()->getManager()->flush();
        //$genref = $eventRepo->find($event->getId());
        return $this->handleView($this->view($event));
    }

    /**
     * recupere les commentaires d'un event
     * @Rest\Get("/json_eventcomment/{id}/{offset}/{limit}")
     */
    public function eventcommentjson(MediaExtension $mediaextension, Request $request, CommentEventRepository $commentRepo) {
        $offset = $request->get('offset');
        $limit = $request->get('limit');

        $comments = $commentRepo->findBy(['event' => $request->get('id')], ['publishedAt' => 'DESC'], $limit, $offset);
        foreach ($comments as $comment) {
            $avatarpath = $mediaextension->path($comment->getAuthor()->getPhoto(), 'normal');
            $comment->setAuthorPhotoUrl($avatarpath);
        }
        return $this->handleView($this->view($comments));
    }

    /**
     * permet de publier les commentaires sur un event
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/json_eventcommentpublier/{id}")
     */
    public function eventcommentpublierjson(MediaExtension $mediaextension, Request $request, Event $event) {
        $data = json_decode($request->getContent(), true);
        $comment = new CommentEvent();
        $comment->setAuthor($this->getUser());
        $comment->setEvent($event);
        $comment->setContent($data['comment']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $avatarpath = $mediaextension->path($comment->getAuthor()->getPhoto(), 'normal');
        $comment->setAuthorPhotoUrl($avatarpath);

        return $this->handleView($this->view($comment));
    }

    /**
     * permet de gerer les achats des tickets sur un event
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/json_manage_ticket/{id}")
     */
    public function eventmanageticketjson(Request $request, Ticket $ticket, GestionTicketRepository $gestTicketRepo, GuestUserRepository $guestRepo, EventDispatcherInterface $eventDispatcher) {
        $data = json_decode($request->getContent(), true);
        $prix = $data['prix'];

        if ($prix == 0) {//gratuit
            if ($this->getUser()) {
                //on voit s'il a deja participe
                $result = $gestTicketRepo->findOneBy(['user' => $this->getUser(), 'ticket' => $ticket]);
                if ($result !== null) {
                    $reponse = new JsonResponse(array('message' => "Vous avez déjà fait une demande de billet pour cet évènement."), Response::HTTP_UNAUTHORIZED);
                    $reponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
                    return $reponse;
                } else {
                    $gestionTicket = new GestionTicket();
                    $gestionTicket->setUser($this->getUser());
                    $gestionTicket->setTicket($ticket);
                    $gestionTicket->setNombre(1);
                    $gestionTicket->setPrix(0);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($gestionTicket);
                    $em->flush();
                    //on declenche les mails
                    $event = new GenericEvent($gestionTicket);
                    $eventDispatcher->dispatch(Events::TICKET_RESERVED, $event);
                }
            } else {
                //on voit si le guest est enregistré
                $guest = $guestRepo->findOneBy(['email' => $data['email']]);
                if ($guest !== null) {
                    //on voit s'il a deja le billet
                    $result = $gestTicketRepo->findOneBy(['guest' => $guest, 'ticket' => $ticket]);
                    if ($result !== null) {
                        $reponse = new JsonResponse(array('message' => "Vous avez déjà fait une demande de billet pour cet évènement."), Response::HTTP_UNAUTHORIZED);
                        $reponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
                        return $reponse;
                    } else {
                        $gestionTicket = new GestionTicket();
                        $gestionTicket->setGuest($guest);
                        $gestionTicket->setTicket($ticket);
                        $gestionTicket->setNombre(1);
                        $gestionTicket->setPrix(0);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($gestionTicket);
                        $em->flush();
                        //on declenche les mails
                        $event = new GenericEvent($gestionTicket);
                        $eventDispatcher->dispatch(Events::TICKET_RESERVED, $event);
                    }
                } else {
                    $guest = new GuestUser();
                    $guest->setName($data['nom']);
                    $guest->setEmail($data['email']);
                    $guest->setTel($data['tel']);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($guest);

                    $gestionTicket = new GestionTicket();
                    $gestionTicket->setGuest($guest);
                    $gestionTicket->setTicket($ticket);
                    $gestionTicket->setNombre(1);
                    $gestionTicket->setPrix(0);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($gestionTicket);
                    $em->flush();
                    //on declenche les mails
                    $event = new GenericEvent($gestionTicket);
                    $eventDispatcher->dispatch(Events::TICKET_RESERVED, $event);
                }
            }

            $reponse = new JsonResponse(array('message' => "Vous recevrez votre ticket par mail. Vous ne pourrez plus faire une autre demande"), Response::HTTP_OK);
            $reponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            return $reponse;
        } else {

            $nombre = $data['nombre'];
            $prix = $nombre * $ticket->getPrix();

            //on voit s'il a payé avant de persister et envoyer des mails


            if ($this->getUser()) {
                $gestionTicket = new GestionTicket();
                $gestionTicket->setUser($this->getUser());
                $gestionTicket->setTicket($ticket);
                $gestionTicket->setNombre($nombre);
                $gestionTicket->setPrix($prix);
                $em = $this->getDoctrine()->getManager();
                $em->persist($gestionTicket);
                $em->flush();
                //on declenche les mails
                $event = new GenericEvent($gestionTicket);
                $eventDispatcher->dispatch(Events::TICKET_RESERVED, $event);
            } else {
                //on voit si le guest est enregistré
                $guest = $guestRepo->findOneBy(['email' => $data['email']]);
                if ($guest !== null) {
                    $gestionTicket = new GestionTicket();
                    $gestionTicket->setGuest($guest);
                    $gestionTicket->setTicket($ticket);
                    $gestionTicket->setNombre($nombre);
                    $gestionTicket->setPrix($prix);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($gestionTicket);
                    $em->flush();
                    //on declenche les mails
                    $event = new GenericEvent($gestionTicket);
                    $eventDispatcher->dispatch(Events::TICKET_RESERVED, $event);
                } else {
                    $guest = new GuestUser();
                    $guest->setName($data['nom']);
                    $guest->setEmail($data['email']);
                    $guest->setTel($data['tel']);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($guest);

                    $gestionTicket = new GestionTicket();
                    $gestionTicket->setGuest($guest);
                    $gestionTicket->setTicket($ticket);
                    $gestionTicket->setNombre($nombre);
                    $gestionTicket->setPrix($prix);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($gestionTicket);
                    $em->flush();
                    //on declenche les mails
                    $event = new GenericEvent($gestionTicket);
                    $eventDispatcher->dispatch(Events::TICKET_RESERVED, $event);
                }
            }
            $reponse = new JsonResponse(array('message' => "Vous recevrez votre ticket par mail. Vous pourrez en faire " . $nombre . " copies."), Response::HTTP_OK);
            $reponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            return $reponse;
        }

        return $this->handleView($this->view([]));
    }

    /**
     * permet a un user de kossa de voter
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/json_uservote/{artisteid}")
     */
    public function awarduservotejson(Request $request, CategorieAwardRepository $catawardRepo, ArtisteRepository $artisteRepo) {
        $user = $this->getUser();
        $artisteid = $request->get('artisteid');
        $artiste = $artisteRepo->find($artisteid);
        $categorie = $artiste->getCategorieAward();

        $categorieaward = $catawardRepo->findByCategorieUser($categorie, $user);
        if ($categorieaward == null) {
            $artiste->addUser($user);
            $this->getDoctrine()->getManager()->flush();
            return $this->handleView($this->view([]));
        } else {
            $reponse = new JsonResponse(array('message' => "contenu introuvable"), Response::HTTP_UNAUTHORIZED);
            $reponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            return $reponse;
        }
    }

    /**
     * permet a un invité de voter
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/json_guestvote/{artisteid}")
     */
    public function awardguestvotejson(Request $request, CategorieAwardRepository $catawardRepo, ArtisteRepository $artisteRepo, GuestUserRepository $guestRepo) {
        $artisteid = $request->get('artisteid');
        $artiste = $artisteRepo->find($artisteid);
        $categorie = $artiste->getCategorieAward();

        $data = json_decode($request->getContent(), true);
        $nom = $data['nom'];
        $email = $data['email'];

        $categorieaward = $catawardRepo->findByCategorieGuest($categorie, $email);
        if ($categorieaward == null) {
            //on voit si le guest est en bd
            $guest = $guestRepo->findOneBy(['email' => $email]);
            if ($guest == null) {
                $guest = new GuestUser();
                $guest->setName($nom);
                $guest->setEmail($email);
                $em = $this->getDoctrine()->getManager();
                $em->persist($guest);
            }

            $artiste->addGuest($guest);
            $this->getDoctrine()->getManager()->flush();

            if ($request->hasSession() && ($session = $request->getSession())) {
                $session->set('kossa_event_artiste_vote_' . $artiste->getId(), true);
            }

            return $this->handleView($this->view([]));
        } else {
            $reponse = new JsonResponse(array('message' => "contenu introuvable"), Response::HTTP_UNAUTHORIZED);
            $reponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            return $reponse;
        }
    }

}
