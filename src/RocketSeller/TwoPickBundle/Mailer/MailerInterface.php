<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RocketSeller\TwoPickBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

/**
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface MailerInterface
{
    /**
     * Send an email to a user to confirm the account creation
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function sendConfirmationEmailMessage(UserInterface $user);

    /**
     * Send an email to a user to confirm the password reset
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function sendResettingEmailMessage(UserInterface $user);

    /**
     * Send an welcome email
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function sendWelcomeEmailMessage(UserInterface $user);

    /**
     * Send an reminder email
     *
     * @param String $toEmail
     *
     * @return void
     */
    public function sendReminderEmailMessage(UserInterface $user,$toEmail);

    /**
     * Send help message
     * @param String $name
     * @param String $fromEmail
     * @param String $subject
     * @param String $ip
     * @param number $phone
     *
     * @return void
     */
    public function sendHelpEmailMessage($name, $fromEmail,$subject,$message,$ip,$phone);

    /**
     * Send an reminder email
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function sendDaviplataMessage(UserInterface $user);
}
