<?php

declare(strict_types=1);

namespace App;

class UserEmailSender implements UserEmailSenderInterface
{
    /**
     * @param string $oldEmail
     * @param string $newEmail
     *
     * @return void
     *
     * @throws EmailSendException
     */
    public function sendEmailChangedNotification(string $oldEmail, string $newEmail): void
    {
        Mailer::send(
            $oldEmail,
            "Your email has been changed",
            "From $oldEmail to $newEmail"
        );
    }
}
