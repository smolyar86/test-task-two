<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class UserEmailChangerService
{
    private PDO $db;
    private UserEmailSenderInterface $notifySender;

    public function __construct(PDO $db, UserEmailSenderInterface $notifySender)
    {
        $this->db = $db;
        $this->notifySender = $notifySender;
    }

    /**
     * @param int $userId
     * @param string $email
     *
     * @return void
     *
     * @throws PDOException
     * @throws EmailSendException
     * @throws UserNotFoundException
     */
    public function changeEmail(int $userId, string $email): void
    {
        try {
            $this->db->beginTransaction();

            $user = $this->getUser($userId);

            if ($user === false) {
                throw new UserNotFoundException();
            }

            if ($user['email'] === $email) {
                return;
            }

            $this->change($userId, $email);
            $this->db->commit();

            $this->notifySender->sendEmailChangedNotification(
                $user['email'],
                $email
            );
        } catch (PDOException|UserNotFoundException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $userId
     * @return array|false
     *
     * @throws PDOException
     */
    private function getUser(int $userId): array|false
    {
        $select = $this->db->prepare('SELECT * FROM users WHERE id = :id FOR UPDATE');
        $select->bindParam(':id', $userId, PDO::PARAM_INT);
        $select->execute();
        return $select->fetch();
    }

    /**
     * @param int $userId
     * @param string $email
     * @return void
     *
     * @throws PDOException
     */
    private function change(int $userId, string $email): void
    {
        $update = $this->db->prepare('UPDATE users SET email = :email WHERE id = :id');

        $update->bindParam(':id', $userId, PDO::PARAM_INT);
        $update->bindParam(':email', $email);
        $update->execute();
    }
}
