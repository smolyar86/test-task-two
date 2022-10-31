<?php

declare(strict_types=1);

namespace App;

use Exception;

class Mailer
{
    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return void
     * @throws EmailSendException
     */
    public static function send(string $to, string $subject, string $message): void
    {
        try {
            $info = sprintf(
                '%s - to:%s message:%s',
                date('Y-m-d h:i:s'),
                $to,
                $message
            );

            file_put_contents(
                '../mails.log',
                $info . PHP_EOL,
                FILE_APPEND
            );
        } catch (Exception $e) {
            throw new EmailSendException(previous: $e);
        }
    }
}
