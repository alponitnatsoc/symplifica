<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RocketSeller\TwoPickBundle\Listener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
// use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use RocketSeller\TwoPickBundle\RocketSellerTwoPickBundle;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use RocketSeller\TwoPickBundle\Mailer\MailerInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class EmailConfirmationListener implements EventSubscriberInterface
{
    private $mailer;
    private $tokenGenerator;
    private $router;
    private $session;

    public function __construct(MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, UrlGeneratorInterface $router, SessionInterface $session)
    {
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->router = $router;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'authenticate2',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onChangePasswordComplete'
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();

        $user->setEnabled(false);
        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }
        $this->mailer->sendEmailByTypeMessage(array('emailType'=>'confirmation','user'=>$user));
        $this->session->set('fos_user_send_confirmation_email/email', $user->getEmail());
        $url = $this->router->generate('fos_user_registration_check_email');
        $event->setResponse(new RedirectResponse($url));
    }

    public function authenticate2(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();
        if (!$user->isEnabled()) {
            return;
        }
        $this->mailer->sendEmailByTypeMessage(array('emailType'=>'welcome','user'=>$user));
    }

    public function onChangePasswordComplete(FormEvent $event)
    {
        $url = $this->router->generate('show_dashboard');
        $event->setResponse(new RedirectResponse($url));
    }
}
