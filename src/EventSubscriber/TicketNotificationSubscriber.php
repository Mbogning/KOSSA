<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Entity\News\Comment;
use App\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Notifies post's author about new comments.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class TicketNotificationSubscriber implements EventSubscriberInterface {

    private $mailer;
    private $translator;
    private $urlGenerator;
    private $sender;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator, $sender) {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->sender = $sender;
    }

    public static function getSubscribedEvents(): array {
        return [
            Events::TICKET_RESERVED => 'onTicketReserved',
        ];
    }

    public function onTicketReserved(GenericEvent $event): void {
        /** @var GestionTicket $gestionTicket */
        $gestionTicket = $event->getSubject();
        if ($gestionTicket->getUser()) {
            $name = $gestionTicket->getUser()->getNom();
            $email = $gestionTicket->getUser()->getEmail();
        } else {
            $name = $gestionTicket->getGuest()->getName();
            $email = $gestionTicket->getGuest()->getEmail();
        }

        $linkToPost = $this->urlGenerator->generate('kossa_event_event', [
            'slug' => $gestionTicket->getTicket()->getEvent()->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);

        if ($gestionTicket->getPrix() == 0) {
            $subject = $this->translator->trans('Votre participation à un évènement');
        } else {
            $subject = $this->translator->trans('Votre ticket à un évènement');
        }
        $body = $this->translator->trans('notification.ticket_created.description', [
            '%title%' => $gestionTicket->getTicket()->getEvent()->getTitre(),
            '%link%' => $linkToPost,
            '%name%' => $name
        ]);

        // Symfony uses a library called SwiftMailer to send emails. That's why
        // email messages are created instantiating a Swift_Message class.
        // See https://symfony.com/doc/current/email.html#sending-emails
        $message = (new \Swift_Message())
                ->setSubject($subject)
                ->setTo([$email =>$name]/* $email */)
                ->setFrom([$this->sender => 'Kossa'])
                ->setBody($body, 'text/html')
        ;

        // In config/packages/dev/swiftmailer.yaml the 'disable_delivery' option is set to 'true'.
        // That's why in the development environment you won't actually receive any email.
        // However, you can inspect the contents of those unsent emails using the debug toolbar.
        // See https://symfony.com/doc/current/email/dev_environment.html#viewing-from-the-web-debug-toolbar
        $this->mailer->send($message);
    }

}
